<?php

namespace App\Tables\Task;

use App\Enums\Documents\IncomeDocumentFields;
use App\Enums\Task\TaskStatus;
use App\Http\Resources\Web\TableCollectionResource;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($tasks)
    {
        $tasksArray = [];
        for ($i = 0; $i < count($tasks); $i++) {
            $tasksArray[$i] = $tasks[$i]->toArray();
            $tasksArray[$i]['id'] = $tasks[$i]->id;

            $tasksArray[$i]['end_time'] = ' - ';

            if($tasks[$i]->status == TaskStatus::DONE){
                $tasksArray[$i]['end_time'] = $tasks[$i]->log->created_at;
            }else if($tasks[$i]->status == TaskStatus::TO_DO){
                $tasksArray[$i]['progress'] = $this->calculateProgress($tasks[$i]->document);
            }

            $tasksArray[$i]['formation_type'] = $tasks[$i]->formation_type?->label();
            $tasksArray[$i]['name'] = $tasks[$i]->name;
            $tasksArray[$i]['type'] = $tasks[$i]->type->toArray();
            $type = $tasks[$i]->type;
            $tasksArray[$i]['type'] = [
                'id' => $type->id,
                'key' => $type->key,
                'name' => $type->name,
                'is_system' => $type->is_system,
                'created_at' => $type->created_at,
                'updated_at' => $type->updated_at,
                'creator_company_id' => $type->creator_company_id,
                'deleted_at' => $type->deleted_at,
            ];

            $executorsData = $tasks[$i]->executorUsers;
            $executors = [];
            if ($executorsData && count($executorsData) > 0) {
                $executors = $executorsData
                    ->map(fn($u)
                        => [
                        'id' => $u->id,
                        'name' => $u->name,
                        'surname' => $u->surname,
                        'patronymic' => $u->patronymic,
                    ])
                    ->values();
            }

            $tasksArray[$i]['executors'] = $executors;

            $document = $tasks[$i]->document;

            $documentData = $document->data();

            $skuTable = collect($documentData['sku_table'] ?? []);
            $tasksArray[$i]['progress'] = [
                'total' => $skuTable->count(),
                'current' => $skuTable->where('processed', true)->count(),
            ];
        }

        return TableCollectionResource::make(array_values($tasksArray))->setTotal($tasks->total());
    }

    private function calculateProgress($document)
    {
        $products = $document->data['sku_table'] ?? [];
        if (empty($products)) {
            return [];
        }

        $data = [];

        foreach ($products as $product) {
            $productId = $product['id'];

            $progressCurrent = 0;
            $progressTotal = $product['quantity'] ?? 0;

            $leftovers = IncomeDocumentLeftover::with(['package', 'container'])
                ->where('document_id', $this->id)
                ->where('goods_id', $productId)
                ->get();

            foreach ($leftovers as $leftover) {
                $quantity = $leftover->quantity * $leftover->package->main_units_number;
                $progressCurrent += $quantity;
            }


            $data[] = [
                'progress' => [
                    'current' => $progressCurrent,
                    'total' => $progressTotal,
                    'measurement_unit' => $product['unit'] ?? ''
                ],
            ];
        }

        return $data;
    }
}
