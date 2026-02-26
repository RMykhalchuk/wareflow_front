<?php

namespace App\Services\Web\Document\Outcome;

use App\Enums\Documents\OutcomeDocumentFields;
use App\Enums\Task\OutcomeTaskType;
use App\Enums\Task\TaskFormationType;
use App\Enums\Task\TaskProcessingType;
use App\Enums\Task\TaskStatus;
use App\Models\Dictionaries\DocumentStatus;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\OutcomeDocumentLeftover;
use App\Models\Entities\Task\Task;
use App\Models\Entities\Task\TaskType;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OutcomeDocumentService implements OutcomeDocumentServiceInterface
{
    public function createPickingTask(Document $document): array
    {
        return $this->createTask($document, OutcomeTaskType::PICKING->value);
    }

    public function createControlTask(Document $document): array
    {
        return $this->createTask($document, OutcomeTaskType::CONTROL->value);
    }

    private function createTask($document, $taskType): array
    {
        $this->validateDocumentHasNoLeftovers($document);

        $taskTypeId = $this->getTaskTypeId($taskType);

        $cellId = $this->extractCellId($document);

        $this->updateDocumentStatus($document);

        return Task::create($this->getTaskData(
            $taskTypeId,
            $document->id,
            $cellId,
            $taskType
        ))->toArray();
    }

    private function validateDocumentHasNoLeftovers(Document $document): void
    {
        if (OutcomeDocumentLeftover::where('document_id', $document->id)->exists()) {
            throw new RuntimeException(
                'To proceed, the document must not have any leftovers.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    private function getTaskTypeId($taskType): int
    {
        return Cache::remember("{$taskType}_id", 3600, function () use ($taskType) {
            return TaskType::where('key', $taskType)->first()->id;
        });
    }

    private function extractCellId(Document $document): ?string
    {
        $documentData = $document->data();

        return $documentData['header_ids'][OutcomeDocumentFields::ALLOCATION->value . '_id'] ?? null;
    }

    private function updateDocumentStatus(Document $document): void
    {
        $statusId = Cache::remember('document_status_process_id', 3600, function () {
            return DocumentStatus::where('key', 'process')->value('id');
        });

        $document->update(['status_id' => $statusId]);
    }

    private function getTaskData(int $typeId, string $documentId, ?string $cellId, string $kind = ''): array
    {
        return [
            'processing_type' => TaskProcessingType::DEFAULT,
            'formation_type' => TaskFormationType::PLANNING,
            'type_id' => $typeId,
            'kind' => $kind,
            'executors' => null,
            'status' => TaskStatus::CREATED->value,
            'document_id' => $documentId,
            'priority' => 0,
            'cell_id' => $cellId,
        ];
    }

    /**
     * Two-step manual processing for outcome documents.
     * First call (no leftovers yet): sets status to "process" (В роботі).
     * Second call (leftovers added): decrements Leftover quantities from OutcomeDocumentLeftovers
     * and sets status to "done" (Проведено).
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

            $outcomeLeftovers = OutcomeDocumentLeftover::where('document_id', $document->id)->get();

            if ($outcomeLeftovers->isEmpty()) {
                $statusId = Cache::remember('document_status_process_id', 3600, function () {
                    return DocumentStatus::where('key', 'process')->value('id');
                });
                $document->update(['status_id' => $statusId]);
                return;
            }

            foreach ($outcomeLeftovers as $item) {
                $item->leftover()->lockForUpdate()->first()?->decrement('quantity', $item->quantity);
            }

            $doneStatusId = Cache::remember('document_status_done_id', 3600, function () {
                return DocumentStatus::where('key', 'done')->value('id');
            });
            $document->update(['status_id' => $doneStatusId]);
        });
    }
}
