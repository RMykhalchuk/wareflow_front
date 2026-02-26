<?php

namespace App\Services\Web\Document\Income;


use App\Enums\Documents\IncomeDocumentFields;
use App\Enums\Task\TaskFormationType;
use App\Enums\Task\TaskProcessingType;
use App\Enums\Task\TaskStatus;
use App\Models\Dictionaries\DocumentStatus;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Task\Task;
use App\Models\Entities\Task\TaskType;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class IncomeDocumentService implements IncomeDocumentInterface
{
    /**
     * Create a processing task for the given document.
     *
     * @throws RuntimeException
     */
    public function createTask(Document $document): Task
    {
        if (IncomeDocumentLeftover::where('document_id', $document->id)->exists()) {
            throw new RuntimeException(
                'To proceed, the document must not have any leftovers.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $taskTypeId = TaskType::where('key', 'income')->first('id')['id'];

        $documentData = $document->data();

        $cellId = $documentData['header_ids'][IncomeDocumentFields::ALLOCATION->value.'_id'] ?? null;

        $statusId = DocumentStatus::where('key', 'process')->first()->id;

        $document->update(['status_id' => $statusId]);

        return Task::create(
            [
                'processing_type' => TaskProcessingType::DEFAULT,
                'formation_type' => TaskFormationType::PLANNING,
                'type_id' => $taskTypeId,
                'kind' => '',
                'executors' => null,
                'status' => TaskStatus::CREATED->value,
                'document_id' => $document->id,
                'priority' => 0,
                'cell_id' => $cellId
            ]);
    }

    /**
     * Process all leftovers from the income document and create new Leftover records.
     * First call (no leftovers yet): sets status to "process" (В роботі).
     * Second call (leftovers added): creates Leftover records and sets status to "done" (Проведено).
     */
    public function process(Document $document): void
    {
        DB::transaction(function () use ($document) {
            if ($document->status->key === 'done') {
                throw new RuntimeException(
                    'Document is already processed.',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $incomeLeftovers = IncomeDocumentLeftover::where('document_id', $document->id)->get();

            if ($incomeLeftovers->isEmpty()) {
                throw new RuntimeException(
                    'You should add at least 1 leftover for manual processing.',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $documentData = $document->data();
            $cellId = $documentData['header_ids'][IncomeDocumentFields::ALLOCATION->value . '_id'] ?? null;

            foreach ($incomeLeftovers as $item) {
                Leftover::create(
                    [
                        'goods_id' => $item->goods_id,
                        'cell_id' => $cellId,
                        'quantity' => $item->quantity,
                        'batch' => $item->batch,
                        'manufacture_date' => $item->manufacture_date,
                        'expiration_term' => $item->expiration_term,
                        'bb_date' => $item->bb_date,
                        'package_id' => $item->package_id,
                        'warehouse_id' => $document->warehouse_id,
                        'has_condition' => $item->has_condition,
                        'container_id' => $item->container_id,
                    ]);
            }

            $doneStatusId = DocumentStatus::where('key', 'done')->first()->id;
            $document->update(['status_id' => $doneStatusId]);
        });
    }
}
