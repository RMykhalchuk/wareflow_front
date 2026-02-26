<?php

namespace App\Services\Terminal\Task\Income;

use App\Enums\ContainerRegister\ContainerRegisterStatus;
use App\Enums\Documents\IncomeDocumentFields;
use App\Enums\Task\TaskStatus;
use App\Http\Requests\Terminal\Income\ClosePositionRequest;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Package;
use App\Models\Entities\Task\Task;
use App\Models\Entities\Task\TaskType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IncomeTaskService implements IncomeTaskInterface
{

    public function getLatest(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $userWarehouse = $user->currentWarehouseId;

        $incomeTypeId = $this->getIncomeTypeId();

        $tasks = $this->buildTasksQuery($request, $userId, $userWarehouse, $incomeTypeId)
            ->latest()
            ->limit(20)
            ->get();

        $this->enrichTasksWithExecutorsAndProgress($tasks);

        return [
            'current' => $tasks->whereIn('status', [
                TaskStatus::CREATED,
                TaskStatus::TO_DO,
                TaskStatus::IN_PROGRESS
            ])->values(),
            'archive' => $tasks->where('status', TaskStatus::DONE)->values(),
        ];
    }

    public function productInfo(Goods $goods): array
    {
        $packages = Package::where('goods_id', $goods->id)
            ->get(['id', 'name', 'main_units_number'])
            ->each->setAppends([])
            ->makeHidden(['barcodeString', 'canEdit']);

        $containers = ContainerRegister::query()->get(['id', 'code']);

        $goods->loadMissing(['measurement_unit:id,name,key', 'barcodes']);

        return [
            'product' => $goods,
            'dictionary' => [
                'packages' => $packages,
                'containers' => $containers,
            ],
        ];
    }

    public function getProductViewData(Task $task, string $productId): array
    {
        $skuTable = $this->getSkuTable($task->document);

        $product = collect($skuTable)->firstWhere('id', $productId);

        if (!$product) {
            abort(404, "Product not found for this task");
        }

        $leftovers = IncomeDocumentLeftover::with(
            [
                'package:packages.id,name,main_units_number',
                'container:id,code',
                'goods.measurement_unit:id,name,key',
            ])
            ->where('document_id', $task->document_id)
            ->where('goods_id', $product['id'])
            ->get();

        $packages = (new Package)
            ->setAppends([])
            ->where('goods_id', $productId)
            ->get(['id', 'name', 'main_units_number']);


        $containers = ContainerRegister::query()->get(['id', 'code']);

        $progress = $this->calculateProgress($leftovers, $product['quantity']);

        $executorsData = $task->executorUsers;
        $executors = [];
        if ($executorsData && count($executorsData) > 0) {
            $executors = $executorsData
                ->map(fn($u)
                    => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'surname' => $u->surname,
                    'patronymic' => $u->patronymic,
                ])
                ->values();
        }

        $goods = Goods::find($productId);

        return [
            'task_number' => $task->local_id,
            'is_archived' => $task->status === TaskStatus::DONE,
            'product' => $product,
            'leftovers' => $leftovers,
            'progress' => $progress,
            'dictionary' => [
                'packages' => $packages,
                'containers' => $containers,
                'expiration_date' => $goods->expiration_date
            ],
            'executors' => $executors,
        ];
    }

    public function storeItems(ClosePositionRequest $request, Document $document, string $goodsId): array
    {
        return DB::transaction(function () use ($request, $document, $goodsId) {
            // Отримуємо максимальний local_id з блокуванням
            $lastRow = IncomeDocumentLeftover::where('document_id', $document->id)
                ->orderByDesc('local_id')
                ->lockForUpdate()
                ->first();

            $maxLocalId = $lastRow?->local_id ?? 0;

            $tableId = 0;
            $newLeftovers = collect($request->leftovers)
                ->where('new_log', true)
                ->map(function ($item) use ($document, $goodsId, &$tableId, &$maxLocalId) {
                    $tableId++;
                    $maxLocalId++;

                    return [
                        'id' => Str::uuid(),
                        'local_id' => $maxLocalId, // ✅ Інкрементуємо тут
                        'table_id' => $tableId,
                        'container_id' => $item['container_id'],
                        'document_id' => $document->id,
                        'goods_id' => $goodsId,
                        'has_condition' => $item['has_condition'] ?? true,
                        'batch' => $item['batch'],
                        'quantity' => $item['quantity'],
                        'package_id' => $item['package_id'],
                        'manufacture_date' => $item['manufacture_date'],
                        'bb_date' => $item['bb_date'],
                        'expiration_term' => $item['expiration_term'],
                        'creator_id' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })
                ->values()
                ->toArray();

            $documentData = $document->data();

            if (!empty($documentData['sku_table'])) {
                foreach ($documentData['sku_table'] as &$skuItem) {
                    if ($skuItem['id'] === $goodsId) {
                        $skuItem['processed'] = true;
                        break;
                    }
                }
                $document->data = json_encode($documentData);
                $document->save();
            }

            // Перевіряємо чи це перші leftovers для документа
            $isFirstLeftover = !IncomeDocumentLeftover::where('document_id', $document->id)->exists();

            // Масовий insert для продуктивності
            IncomeDocumentLeftover::insert($newLeftovers);

            // Оновлюємо task якщо це перші leftovers
            if ($isFirstLeftover) {
                Task::where('document_id', $document->id)->update([
                                                                      'started_at' => Carbon::now(),
                                                                      'status' => TaskStatus::IN_PROGRESS->value,
                                                                      'executors' => [Auth::id()]
                                                                  ]);
            }

            return $newLeftovers;
        });
    }


    private function getSkuTable(Document $document): array
    {
        return $document->data()['sku_table'] ?? [];
    }

    private function calculateProgress(Collection $leftovers, float|int $total): array
    {
        $current = $leftovers->sum(function ($l) {
            return $l->quantity * $l->package->main_units_number;
        });

        return [
            'total'   => round((float) $total, 3),
            'current' => round($current, 3),
        ];
    }

    private function getIncomeTypeId(): int
    {
        return Cache::remember('task_type_income_id', 3600, function () {
            return TaskType::where('key', 'income')->value('id');
        });
    }

    private function buildTasksQuery(
        Request $request,
        string $userId,
        ?string $userWarehouse,
        int $incomeTypeId
    ): \Illuminate\Database\Eloquent\Builder
    {
        $query = Task::with(['document'])
            ->inCurrentWarehouse()
            ->where('type_id', $incomeTypeId);

        if ($userWarehouse) {
            $query->whereHas('document', function ($q) use ($userWarehouse) {
                $q->where('warehouse_id', $userWarehouse);
            });
        }

        $this->applyExecutorFilter($query, $request, $userId);
        $this->applyDateFilters($query, $request);
        $this->applyTaskIdFilter($query, $request);

        return $query;
    }

    private function applyExecutorFilter($query, Request $request, string $userId): void
    {
        if ($request->filled('executor')) {
            if ($request->executor === 'me') {
                $query->whereJsonContains('executors', $userId);
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
                $q->whereJsonContains('executors', $userId)
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
            $query->where('local_id', 'LIKE', $request->task_id . '%');
        }
    }

    private function enrichTasksWithExecutorsAndProgress($tasks): void
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
            $this->attachProviderAndZoneToTask($task, $companies);
            $this->calculateTaskProgress($task);

            $task->makeHidden('document');
        });
    }

    private function extractCompanyIds($tasks): array
    {
        $companyIds = [];

        foreach ($tasks as $task) {
            $documentData = $task->document?->data() ?? [];
            $companyId = $documentData['header_ids'][IncomeDocumentFields::SUPPLIER->value . '_id'] ?? null;

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

    private function attachProviderAndZoneToTask($task, $companies): void
    {
        $documentData = $task->document?->data() ?? [];
        $companyId = $documentData['header_ids'][IncomeDocumentFields::SUPPLIER->value . '_id'] ?? null;

        $task->provider = $companyId && isset($companies[$companyId])
            ? ['id' => $companyId, 'name' => $companies[$companyId]->fullName]
            : null;


        $task->zone = $documentData['header'][IncomeDocumentFields::ALLOCATION->value] ?? null;
    }

    private function calculateTaskProgress($task): void
    {
        $documentData = $task->document?->data() ?? [];
        $skuTable = collect($documentData['sku_table'] ?? []);

        $total = $skuTable->count();
        $current = $task->status === TaskStatus::DONE
            ? $total
            : $skuTable->where('processed', true)->count();

        $task->progress = [
            'total'   => $total,
            'current' => $current,
        ];
    }
}
