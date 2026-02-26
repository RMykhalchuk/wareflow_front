<?php

namespace App\Services\Terminal\Completing;


use App\Enums\Documents\OutcomeDocumentFields;
use App\Enums\Task\OutcomeTaskType;
use App\Enums\Task\Picking\LeftoverLogType;
use App\Enums\Task\TaskProcessingType;
use App\Enums\Task\TaskStatus;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\OutcomeDocumentLeftover;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Task\Task;
use App\Models\Entities\Task\TaskType;
use App\Models\Entities\Terminal\TerminalLeftoverLog;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\User;
use App\Services\Web\Document\Outcome\OutcomeDocumentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PickingService implements PickingServiceInterface
{
    public function matchProductsByDocumentInCell(Document $document, Cell $cell): array
    {
        $documentProducts = collect($document->products())->keyBy('id');

        $processedByGoods = OutcomeDocumentLeftover::with(['leftover.package'])
            ->where('document_id', $document->id)
            ->where('processing_type', 'picking')
            ->whereHas('leftover', function ($q) use ($documentProducts) {
                $q->whereIn('goods_id', $documentProducts->keys());
            })
            ->get()
            ->groupBy(fn($item) => $item->leftover->goods_id)
            ->map(function ($items) {
                return $items->sum(fn($i)
                    => $i->quantity * $i->leftover->package->main_units_number
                );
            });


        return Leftover::with(['package', 'goods'])
            ->where('cell_id', $cell->id)
            ->whereIn('goods_id', $documentProducts->keys())
            ->get()
            ->keyBy('goods_id')
            ->filter(function ($leftover) use ($documentProducts, $processedByGoods) {
                $required = $documentProducts[$leftover->goods_id]['quantity'];
                $processed = $processedByGoods[$leftover->goods_id] ?? 0;

                return $processed < $required;
            })
            ->map(fn($l)
                => [
                'name' => $l->goods->name,
                'barcode' => $l->package->barcode?->barcode,
            ])
            ->toArray();
    }

    public function pickUpContainer(Document $document, ContainerRegister $containerRegister): void
    {
        DB::transaction(function () use ($document, $containerRegister) {
            $leftovers = Leftover::where('container_id', $containerRegister->id)->get();

            foreach ($leftovers as $leftover) {
                TerminalLeftoverLog::create([
                    'document_id' => $document->id,
                    'leftover_id' => $leftover->id,
                    'quantity' => $leftover->quantity,
                    'container_id' => $leftover->container_id,
                    'package_id' => $leftover->package_id,
                    'type' => LeftoverLogType::PICK->value,
                ]);
            }
        });
    }

    public function takeLeftover(Document $document, $data, $leftoverType): TerminalLeftoverLog
    {
        return TerminalLeftoverLog::create(
            [
                'document_id' => $document->id,
                'leftover_id' => $data['leftover_id'],
                'quantity' => $data['quantity'],
                'container_id' => $data['container_id'],
                'package_id' => $data['package_id'],
                'type' => $leftoverType
            ]);
    }


    public function getLatest(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $userWarehouse = $user->currentWarehouseId;

        $pickingTypeId = $this->getPickingTypeId();

        $tasks = $this->buildTasksQuery($request, $userId, $userWarehouse, $pickingTypeId)
            ->latest()
            ->limit(20)
            ->get();

        [$current, $archive] = $this->enrichTasksWithExecutorsAndProgress($tasks);


        return [
            'current' => $current,
            'archive' => $archive
        ];
    }

    public function moveToControl(Document $document, Goods $goods): void
    {

        $hasTask = Task::where('type_id', OutcomeTaskType::PICKING->value)
            ->where('document_id', $document->id)->exists();

        if (!$hasTask) {
            $outcomeDocumentService = resolve(OutcomeDocumentServiceInterface::class);

            $outcomeDocumentService->createControlTask($document);
        }

        TerminalLeftoverLog::apply($document->id, $goods->id, TaskProcessingType::PICKING, Auth::id());
    }

    private function getPickingTypeId(): int
    {
        return Cache::remember('task_type_picking_id', 3600, function () {
            return TaskType::where('key', 'picking')->value('id');
        });
    }

    private function buildTasksQuery(
        Request $request,
        string $userId,
        string $userWarehouse,
        int $pickingTypeId
    ): \Illuminate\Database\Eloquent\Builder
    {
        $query = Task::with(['document'])
            ->inCurrentWarehouse()
            ->where('type_id', $pickingTypeId)
            ->whereHas('document', function ($q) use ($userWarehouse) {
                $q->where('warehouse_id', $userWarehouse);
            });


        $this->applyExecutorFilter($query, $request, $userId);
        $this->applyDateFilters($query, $request);
        $this->applyTaskIdFilter($query, $request);

        return $query;
    }

    private function applyExecutorFilter($query, Request $request, string $userId): void
    {
        if ($request->filled('executor')) {
            if ($request->executor === 'me') {
                $query->whereJsonContains('executors', (string)$userId);
            } else {
                // all (null або [])
                $query->where(function ($q) {
                    $q->whereNull('executors')
                        ->orWhereJsonLength('executors', 0);
                });
            }
        } else {
            // За замовчуванням: мої завдання + загальні
            $query->where(function ($q) use ($userId) {
                $q->whereJsonContains('executors', (string)$userId)
                    ->orWhereNull('executors')
                    ->orWhereJsonLength('executors', 0);
            });
        }
    }

    private function applyDateFilters($query, Request $request): void
    {
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
    }

    private function applyTaskIdFilter($query, Request $request): void
    {
        if ($request->filled('task_id')) {
            $query->where('id', 'LIKE', $request->task_id . '%');
        }
    }

    private function enrichTasksWithExecutorsAndProgress($tasks): array
    {
        $executorIds = $tasks->pluck('executors')
            ->flatten()
            ->filter()
            ->unique()
            ->values();

        if ($executorIds->isEmpty()) {
            $executors = collect();
        } else {
            $executors = User::whereIn('id', $executorIds)->get()->keyBy('id');
        }

        $companyIds = $this->extractCompanyIds($tasks);
        $companies = $this->loadCompanies($companyIds);

        $tasks->each(function ($task) use ($executors, $companies) {
            $this->attachExecutorsToTask($task, $executors);
            $this->attachCustomerToTask($task, $companies);
            $this->calculateTaskProgress($task);

            $task->makeHidden('document');
        });

        return $this->divideTask($tasks);
    }

    private function divideTask($tasks)
    {
        $current = [];
        $archive = [];

        foreach ($tasks as $task) {
            if ($task->status !== TaskStatus::DONE->value) {
                $current[] = $task;
            }else {
                $archive[] = $task;
            }
        }

        return [$current, $archive];
    }

    private function extractCompanyIds($tasks): array
    {
        $companyIds = [];

        foreach ($tasks as $task) {
            $documentData = $task->document?->data() ?? [];
            $companyId = $documentData['header_ids'][OutcomeDocumentFields::CUSTOMER->value . '_id'] ?? null;

            if ($companyId) {
                $companyIds[] = $companyId;
            }
        }

        return array_unique($companyIds);
    }

    private function loadCompanies(array $companyIds)
    {
        if (empty($companyIds)) {
            return collect();
        }

        return Company::with('company')
            ->whereIn('id', $companyIds)
            ->get()
            ->keyBy('id');
    }

    private function attachExecutorsToTask($task, $executors): void
    {
        $taskExecutorIds = $task->executors ?? [];

        $task->setRelation(
            'executors',
            $executors->only($taskExecutorIds)->values()
        );
    }

    private function attachCustomerToTask($task, $companies): void
    {
        $documentData = $task->document?->data() ?? [];
        $companyId = $documentData['header_ids'][OutcomeDocumentFields::CUSTOMER->value . '_id'] ?? null;


        $task->customer = $companyId && isset($companies[$companyId])
            ? $companies[$companyId]->fullName
            : null;
    }

    private function calculateTaskProgress($task): void
    {
        $documentData = $task->document?->data() ?? [];
        $skuTable = collect($documentData['sku_table'] ?? []);

        $task->progress = [
            'total' => $skuTable->count(),
            'current' => $skuTable->where('processed', true)->count(),
        ];
    }
}
