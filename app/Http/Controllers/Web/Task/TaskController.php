<?php

namespace App\Http\Controllers\Web\Task;


use App\Enums\Task\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Task\TaskRequest;
use App\Models\Entities\Task\Task;
use App\Models\User;
use App\Services\Web\Task\TaskServiceInterface;
use App\Tables\Task\TableFacade;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function __construct(
        private TaskServiceInterface $taskService
    ) {}

    public function index()
    {
        $tasksCount = Task::count();
        return view('tasks.index', compact('tasksCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->validated());
        return response()->json($task, 201);
    }


    public function show(Task $task)
    {
        $task->loadMissing(['items', 'document']);

        return view('tasks.view', $this->taskService->getShowData($task));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
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

    public function filter(Request $request)
    {
        $warehouseId = \Auth::user()->currentWarehouseId;

        return TableFacade::getFilteredData($warehouseId);
    }

    public function assignExecutor(Task $task, User $user)
    {
        $this->taskService->assignExecutor($task, $user);

        return response(['success' => 'Executor assigned']);
    }

    public function removeExecutor(Task $task, User $user)
    {
        $this->taskService->removeExecutor($task, $user);

        return response(['success' => 'Executor removed']);
    }

    public function taskItemFilter(Task $task, string $productId)
    {
        $task->loadMissing(['document']);

        return \App\Tables\TaskItem\TableFacade::getFilteredData($task->document, $productId);
    }

    public function setPriority(Task $task, int $priority)
    {
        $task->update(['priority' => $priority]);

        return response(['success' => 'Priority changed'], 200);
    }

    public function moveInProgress(Task $task)
    {
        $task->update(['status' => TaskStatus::TO_DO->value, 'started_at' => Carbon::now()]);

        return response(['success' => 'Move task in progress'], 200);
    }

    public function cancel(Task $task)
    {
        $this->taskService->cancel($task);

        return response(['success' => 'Task is canceled'], 200);
    }
}
