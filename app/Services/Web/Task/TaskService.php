<?php

namespace App\Services\Web\Task;

use App\Enums\Task\TaskFormationType;
use App\Enums\Task\TaskStatus;
use App\Models\Dictionaries\DocumentStatus;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Task\Task;
use App\Models\User;
use App\Services\Web\Document\DocumentServiceInterface;
use Illuminate\Support\Facades\DB;

class TaskService implements TaskServiceInterface
{
    public function create(array $data): Task
    {
        $createData = $this->prepareData($data);
        $createData['status'] = TaskStatus::CREATED;
        $createData['formation_type'] = TaskFormationType::PLANNING;
        return Task::create($createData);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($this->prepareData($data));
        return $task;
    }

    public function delete(Task $task): bool
    {
        DB::transaction(function () use ($task) {
            $task->items()->delete();
            $task->delete();
        });
    }

    private function prepareData(array $data): array
    {
        // Преобразування масивів у json
        if (isset($data['executors'])) {
            $data['executors'] = json_encode($data['executors']);
        }

        if (isset($data['task_data'])) {
            $data['task_data'] = json_encode($data['task_data']);
        }

        return $data;
    }

    public function assignExecutor(Task $task, User $user): void
    {
        $executors = $task->executors ?? [];

        if (in_array($user->id, $executors)) {
            throw new \Exception('Executor already assigned');
        }

        $executors[] = $user->id;

        $task->update(['executors' => $executors]);
    }

    public function removeExecutor(Task $task, User $user): void
    {
        $executors = array_values(
            array_filter(
                $task->executors ?? [],
                fn($id) => $id !== $user->id
            )
        );

        $task->update(['executors' => $executors]);
    }

    public function getShowData(Task $task): array
    {
        $document = $task->document;
        $documentProducts = $document->data()['sku_table'] ?? [];

        $documentService = resolve(DocumentServiceInterface::class);
        $progressData = $documentService->getProgressData($document, $documentProducts);

        return ['task' => $task, 'document' => $document, 'progressData' => $progressData];
    }

    public function cancel(Task $task): void
    {
        DB::transaction(function () use ($task) {

            $hasPreviousActiveTask = Task::query()
                ->where('document_id', $task->document_id)
                ->where('local_id', '<', $task->local_id)
                ->where('status', '!=', TaskStatus::CANCELED->value)
                ->exists();

            if (! $hasPreviousActiveTask) {
                $documentStatusId = DocumentStatus::where('key', 'created')->value('id');

                Document::whereKey($task->document_id)
                    ->update(['status_id' => $documentStatusId]);
            }

            $task->update(['status' => TaskStatus::CANCELED->value]);
        });

    }
}
