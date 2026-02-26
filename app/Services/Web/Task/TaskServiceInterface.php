<?php

namespace App\Services\Web\Task;

use App\Models\Entities\Task\Task;
use App\Models\User;

interface TaskServiceInterface
{
    public function create(array $data): Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): bool;

    public function assignExecutor(Task $task,  User $user): void;

    public function removeExecutor(Task $task,  User $user): void;

    public function getShowData(Task $task): array;

    public function cancel(Task $task): void;
}
