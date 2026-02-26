<?php

namespace App\Http\Controllers\Terminal\Task;

use App\Enums\Task\TaskProcessingType;
use App\Enums\Task\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Terminal\Picking\MoveToControlRequest;
use App\Http\Requests\Terminal\Picking\StoreLeftoverLogsRequest;
use App\Http\Requests\Terminal\Picking\ViewProductRequest;
use App\Http\Requests\Terminal\TerminalIndexFilterRequest;
use App\Http\Resources\Terminal\Task\Picking\PickingCellViewResource;
use App\Http\Resources\Terminal\Task\Picking\PickingViewResource;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Task\Task;
use App\Models\Entities\Terminal\TerminalLeftoverLog;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use App\Services\Terminal\Completing\PickingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class PickingController extends Controller
{
    public function __construct(private PickingServiceInterface $pickingService) {}

    /**
     * Get latest completing tasks
     */
    public function index(TerminalIndexFilterRequest $request): JsonResponse
    {
        $tasks = $this->pickingService->getLatest($request);

        return response()->json($tasks);
    }

    /**
     * View completing products by task
     * @urlParam task string required The UUID of the task.
     */
    public function view(Task $task): PickingViewResource
    {
        return PickingViewResource::make($task->document);
    }

    /**
     * Get needed product list in location
     */
    public function getProductsByLocation(Document $document, Cell $cell): JsonResource
    {
        $products = $this->pickingService->matchProductsByDocumentInCell($document, $cell);

        return JsonResource::make($products);
    }

    /**
     * Get locations list
     */
    public function getLocations(): JsonResource
    {
        $controlZones = WarehouseZone::whereHas('zoneType', function ($query) {
            $query->where('key', 'operation');
        })->whereHas('zoneSubtype', function ($query) {
            $query->where('key', 'quality_control');
        })->get(['id', 'name']);

        return JsonResource::make($controlZones);
    }

    /**
     * View product
     */
    public function viewProduct(ViewProductRequest $request, Document $document, Cell $cell, Goods $goods): PickingCellViewResource
    {
        $container = $request->validated('container');

        return PickingCellViewResource::make([
            'document' => $document,
            'cell' => $cell,
            'goods' => $goods,
            'container' => $container,
        ]);
    }

    /**
     * Take full container
     */
    public function pickUpContainer(Document $document, ContainerRegister $containerRegister): JsonResponse
    {
        try {
            $this->pickingService->pickUpContainer($document, $containerRegister);

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to pick up container'], 500);
        }
    }

    /**
     * Take leftover/part leftover
     */
    public function takeLeftover(StoreLeftoverLogsRequest $request, Document $document): JsonResponse
    {
        try {
            $data = $request->validated();

            $leftoverType = $data['type'];
            unset($data['type']);

            $leftoverLog = $this->pickingService->takeLeftover($document, $data, $leftoverType);

            return response()->json($leftoverLog->toArray());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to take leftover'], 500);
        }
    }

    /**
     * Move product (leftovers) to quality control
     */
    public function moveToControl(MoveToControlRequest $request, Document $document): JsonResponse
    {
        try {
            $goods = Goods::findOrFail($request->validated('goods_id'));

            $this->pickingService->moveToControl($document, $goods);

            return response()->json(['message' => 'OK']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Goods not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to move to control'], 500);
        }
    }

    /**
     * Finish picking task
     */
    public function finish(Document $document): JsonResponse
    {
        try {
            Task::where('document_id', $document->id)
                ->where('processing_type', TaskProcessingType::PICKING->value)
                ->update(['status' => TaskStatus::DONE->value]);

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to finish task'], 500);
        }
    }

    /**
     * Delete leftover log entry
     */
    public function deleteLeftover(Document $document, Leftover $leftover): JsonResponse
    {
        try {
            $terminalLeftoverLog = TerminalLeftoverLog::where('document_id', $document->id)
                ->where('leftover_id', $leftover->id)
                ->firstOrFail();

            $terminalLeftoverLog->revert();

            return response()->json(['message' => 'OK']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Leftover log not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete leftover'], 500);
        }
    }

    /**
     * Cancel all picking progress for a document
     */
    public function cancelProgress(Document $document): JsonResponse
    {
        try {
            $terminalLeftoverLogs = TerminalLeftoverLog::where('document_id', $document->id)->get();

            foreach ($terminalLeftoverLogs as $terminalLeftoverLog) {
                $terminalLeftoverLog->revert();
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to cancel progress'], 500);
        }
    }
}