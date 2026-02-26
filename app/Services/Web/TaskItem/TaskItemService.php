<?php

namespace App\Services\Web\TaskItem;

use App\Models\Entities\Task\Task;
use App\Models\Entities\Task\TaskItem;

class TaskItemService implements TaskItemServiceInterface
{
    public function addItem(Task $task, array $data): TaskItem
    {
        return $task->items()->create($data);
    }

    public function updateItem(TaskItem $item, array $data): TaskItem
    {
        $item->update($data);
        return $item;
    }

    public function deleteItem(TaskItem $item): bool
    {
        return $item->delete();
    }
}
