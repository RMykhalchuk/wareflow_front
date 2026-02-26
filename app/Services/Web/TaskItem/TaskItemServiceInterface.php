<?php

namespace App\Services\Web\TaskItem;

use App\Models\Entities\Task\Task;
use App\Models\Entities\Task\TaskItem;

interface TaskItemServiceInterface
{
    public function addItem(Task $task, array $data): TaskItem;
    public function updateItem(TaskItem $item, array $data): TaskItem;
    public function deleteItem(TaskItem $item): bool;
}
