<?php

namespace App\Services\Terminal\Task\ManualIncome;

use App\Enums\Task\TaskFormationType;
use App\Enums\Task\TaskStatus;
use App\Models\Dictionaries\DocumentStatus;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Document\DocumentType;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Package;
use App\Models\Entities\Task\Task;
use App\Models\Entities\Task\TaskType;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Services\Web\Document\Income\IncomeDocumentInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManualIncomeService implements ManualIncomeServiceInterface
{
    // =====================================================================
    // MAIN ENTRY
    // =====================================================================

    public function closePosition(array $data): array
    {
        return DB::transaction(function () use ($data) {

            [$document, $task] = $this->getOrCreateDocument($data);

            $createdLeftovers = [];
            $updatedData = $document->data() ?? [];

            // Обробляємо ВСІ leftovers
            foreach ($data['leftovers'] as $leftoverData) {

                // 1) SKU TABLE → отримуємо local_id
                [$updatedData, $localId] = $this->updateDocumentSkuTable(
                    document:  $document,
                    goodsId:   $leftoverData['goods_id'],
                    packageId: $leftoverData['package_id'],
                    quantity:  $leftoverData['quantity'],
                    currentData: $updatedData  // передаємо поточні дані
                );

                // 2) створюємо leftover з table_id = localId
                $leftover = $this->createIncomeLeftover(
                    data:       $leftoverData,
                    documentId: $document->id,
                    localId:    $localId
                );

                $createdLeftovers[] = $leftover;
            }

            $document->update(['data' => json_encode($updatedData)]);

            return [
                'document_id' => $document->id,
                'income_leftovers' => $createdLeftovers,  // множина
                'task_id' => $task->local_id,
                'document_data' => $updatedData
            ];
        });
    }

    public function revertPosition(IncomeDocumentLeftover $leftover): void
    {
        DB::transaction(function () use ($leftover) {

            $document = $leftover->document;
            $docData = $document->data();
            $skuTable = &$docData['sku_table'];

            $mainUnits = $leftover->package->main_units_number * $leftover->quantity;

            $index = $this->findSkuIndexByGoodsId($skuTable, $leftover->goods_id);

            if ($index !== null) {
                $skuTable[$index]['quantity'] -= $mainUnits;

                if ($skuTable[$index]['quantity'] <= 0) {
                    array_splice($skuTable, $index, 1);

                    foreach ($skuTable as $i => &$row) {
                        $row['local_id'] = $i + 1;
                        $row['position'] = $i + 1;
                    }
                }

                $document->update(['data' => json_encode($docData)]);
            }

            $leftover->delete();
        });
    }

    public function revertIncome(Document $document): void
    {
        $statusId = DocumentStatus::where('key', 'canceled')->firstOrFail(['id'])->id;

        DB::transaction(function () use ($document, $statusId) {

            $document->update(['status_id' => $statusId]);

            IncomeDocumentLeftover::where('document_id', $document->id)->delete();
            Task::where('document_id', $document->id)->delete();
        });
    }

    public function closeIncome(Document $document): void
    {
        DB::transaction(function () use ($document) {
            if ($document->status->key === 'process') {

                $incomeDocumentService = resolve(IncomeDocumentInterface::class);
                $incomeDocumentService->process($document);

                $task = Task::where('document_id', $document->id)
                    ->where('kind', 'on_arrival')
                    ->first();

                $task?->update(['status' => TaskStatus::DONE->value, 'executors' => [Auth::id()]]);
            }
        });
    }


    // =====================================================================
    // DOCUMENT HANDLING
    // =====================================================================

    private function getOrCreateDocument(array $data): array
    {
        if (!empty($data['document_id'])) {
            return $this->loadExistingDocument($data['document_id']);
        }

        return $this->createNewDocumentFlow($data);
    }

    private function loadExistingDocument(string $documentId): array
    {
        $document = Document::findOrFail($documentId);

        $task = Task::where('document_id', $document->id)
            ->where('kind', 'on_arrival')
            ->firstOrFail();

        return [$document, $task];
    }

    private function createNewDocumentFlow(array $data): array
    {
        $document = $this->createNewDocument($data);
        $task = $this->createArrivalTask($data, $document->id);

        return [$document, $task];
    }

    private function createNewDocument(array $data): Document
    {
        $documentType = DocumentType::where('kind', 'arrival')->firstOrFail(['id']);
        $documentStatus = DocumentStatus::where('key', 'process')->firstOrFail(['id']);

        return Document::create(
            [
                'type_id' => $documentType->id,
                'status_id' => $documentStatus->id,
                'warehouse_id' => Auth::user()->currentWarehouseId,
                'data' => $this->initDocumentData($data),
                'created_by_system' => true
            ]);
    }

    private function initDocumentData(array $data): string
    {
        $company = Company::findOrFail($data['provider_id']);
        $cellCode = Cell::findOrFail($data['cell_id'])->code;

        return json_encode([
                               "header" => [
                                   "1select_field_1" => $company->fullName,
                                   "2select_field_2" => $cellCode,
                                   "3text_field_3" => "Document from terminal"
                               ],
                               "header_ids" => [
                                   "1select_field_1_id" => $data['provider_id'],
                                   "2select_field_2_id" => $data['cell_id']
                               ],
                               "custom_blocks" => new \stdClass(),
                               "sku_table" => [],
                               "activity_state" => "tasks"
                           ]);
    }

    private function createArrivalTask(array $data, string $documentId): Task
    {
        $taskType = TaskType::where('key', 'income')->firstOrFail(['id']);

        return Task::create(
            [
                'type_id' => $taskType->id,
                'formation_type' => TaskFormationType::DYNAMIC,
                'executors' => [Auth::id()],
                'kind' => 'on_arrival',
                'status' => TaskStatus::IN_PROGRESS->value,
                'document_id' => $documentId,
                'cell_id' => $data['cell_id'],
                'started_at' => Carbon::now()
            ]);
    }

    // =====================================================================
    // INCOME LEFTOVER
    // =====================================================================

    private function createIncomeLeftover(array $data, string $documentId, int $localId): IncomeDocumentLeftover
    {
        $fields = [
            'batch', 'quantity', 'package_id', 'manufacture_date',
            'bb_date', 'new_log', 'expiration_term', 'container_id',
            'has_condition', 'goods_id'
        ];

        $payload = Arr::only($data, $fields);
        $payload['document_id'] = $documentId;
        $payload['table_id'] = $localId;
        $payload['creator_id'] = Auth::id();

        $existing = IncomeDocumentLeftover::where([
            'document_id'     => $documentId,
            'goods_id'        => $payload['goods_id'],
            'package_id'      => $payload['package_id'],
            'batch'           => $payload['batch'],
            'manufacture_date' => $payload['manufacture_date'],
            'bb_date'         => $payload['bb_date'],
            'expiration_term' => $payload['expiration_term'],
            'container_id'    => $payload['container_id'] ?? null,
            'has_condition'   => $payload['has_condition'] ?? true,
        ])->first();

        if ($existing !== null) {
            $existing->increment('quantity', $payload['quantity']);

            return $existing->fresh();
        }

        return IncomeDocumentLeftover::create($payload);
    }


    // =====================================================================
    // SKU TABLE PROCESSING
    // =====================================================================

    /**
     * Returns: [updated_document_data_array, local_id]
     */
    private function updateDocumentSkuTable(
        Document $document,
        string $goodsId,
        string $packageId,
        float $quantity,
        array $currentData = null
    ): array {
        $docData = $currentData ?? $document->data() ?? [];

        $docData['sku_table'] ??= [];
        $skuTable = &$docData['sku_table'];

        $package = Package::findOrFail($packageId);
        $mainUnits = $package->main_units_number * $quantity;

        $index = $this->findSkuIndexByGoodsId($skuTable, $goodsId);

        if ($index !== null) {
            $skuTable[$index]['quantity'] += $mainUnits;
            $localId = $skuTable[$index]['local_id'];
        } else {
            $goods = Goods::withoutGlobalScope(\App\Scopes\CompanyScope::class)->findOrFail($goodsId);
            $localId = count($skuTable) + 1;
            $skuTable[] = $this->createNewSkuRow(
                goodsId:  $goodsId,
                name:     $goods->name,
                quantity: $mainUnits,
                localId:  $localId
            );
        }

        return [$docData, $localId];
    }


    private function findSkuIndexByGoodsId(array $skuTable, string $goodsId): ?int
    {
        foreach ($skuTable as $i => $row) {
            if (isset($row['id']) && $row['id'] == $goodsId) {
                return $i;
            }
        }
        return null;
    }

    // =====================================================================
    // CLOSE / REVERT
    // =====================================================================


    private function createNewSkuRow(string $goodsId, string $name, float|int $quantity, int $localId): array
    {
        return [
            'id' => $goodsId,
            'name' => $name,
            'local_id' => $localId,
            'quantity' => $quantity,
            'position' => $localId,
            'uid' => $localId - 1,
            'boundindex' => $localId - 1,
            'uniqueid' => uniqid(),
            'visibleindex' => $localId - 1,
        ];
    }

}
