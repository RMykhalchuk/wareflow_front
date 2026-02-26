<?php

namespace App\Http\Controllers\Terminal\Task;

use App\Enums\Task\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Terminal\Income\ClosePositionRequest;
use App\Http\Resources\Terminal\Income\IncomeProductResource;
use App\Http\Resources\Terminal\Income\ProductViewResource;
use App\Http\Resources\Web\TaskProductResource;
use App\Models\Dictionaries\DocumentStatus;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Task\Task;
use App\Services\Terminal\Task\Income\IncomeTaskInterface;
use App\Services\Web\Document\Income\IncomeDocumentInterface;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class IncomeController extends Controller
{
    public function __construct(
        private IncomeTaskInterface $incomeTaskService,
        private IncomeDocumentInterface $incomeDocumentService,
    ) {}

    /**
     * Get latest income tasks
     */
    public function index(Request $request)
    {
        $tasks = $this->incomeTaskService->getLatest($request);

        return response()->json($tasks);
    }

    /**
     * View income products by task
     * @urlParam task string required The UUID of the task.
     */
    public function view(Task $task)
    {
        return IncomeProductResource::make($task->document);
    }

    /**
     * Mark task as processed
     *
     * @urlParam task string required The UUID of the task.
     */
    public function process(Request $request, Task $task)
    {
        $this->incomeDocumentService->process($task->document);

        $task->update(['status' => TaskStatus::DONE->value, 'finished_at' => Carbon::now(), 'executors' => [Auth::id()]]);
        $task->logCompleted();

        return response()->json($task);
    }

    /**
     * View product in income task
     * @urlParam task string required The UUID of the task.
     * @urlParam goodsId string required The UUID of the product (goods) in this task.
     */
    public function viewProduct(Task $task, string $goodsId)
    {
        $data = $this->incomeTaskService->getProductViewData($task, $goodsId);
        return new TaskProductResource($data);
    }

    /**
     * Get product info
     * @urlParam task string required The UUID of the task.
     * @urlParam goodsId string required The UUID of the product (goods) in this task.
     */
    public function productInfo(Goods $goods)
    {
        $data = $this->incomeTaskService->productInfo($goods);
        return new ProductViewResource($data);
    }
    /**
     * Close product position (create leftovers)
     * @urlParam task string required The UUID of the task.
     * @urlParam goodsId string required The UUID of the product.
     */
    public function closePosition(ClosePositionRequest $request, Task $task, string $goodsId)
    {
        $newLeftovers = $this->incomeTaskService->storeItems($request, $task->document, $goodsId);

        return response()->json(
            [
                'created' => $newLeftovers
            ]);
    }


}

