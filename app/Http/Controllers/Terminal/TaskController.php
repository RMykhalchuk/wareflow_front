<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Requests\Web\Task\TaskRequest;
use App\Models\Entities\Task\Task;
use App\Services\Web\Task\TaskServiceInterface;
use Illuminate\Http\JsonResponse;

class TaskController {
    public function __construct(private TaskServiceInterface $taskService) {}

    public function index(): JsonResponse
    {
        $tasks = Task::paginate(20);
        return response()->json($tasks);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->validated());
        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        $task = $this->taskService->update($task, $request->validated());
        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->delete($task);
        return response()->json(null, 204);
    }
}
