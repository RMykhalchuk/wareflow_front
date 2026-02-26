<?php

namespace App\Services\Web\Goods\Package;

use App\Models\Entities\Package;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageService implements PackageServiceInterface
{
    /**
     * Створення нових пакувань (підтримує кілька дерев).
     */
    public function store(array $packagesData, string $goodsId): Collection
    {
        return DB::transaction(function () use ($packagesData, $goodsId) {
            $uuids = [];
            $packages = [];

            foreach ($packagesData as $data) {
                $uuids[$data['id']] = (string) Str::uuid();
            }

            foreach ($packagesData as $data) {
                $parentUuid = isset($data['parent_id'])
                    ? ($uuids[$data['parent_id']] ?? null)
                    : null;

                $package = Package::create([
                                               'id' => $uuids[$data['id']],
                                               'goods_id' => $goodsId,
                                               'type_id' => $data['type_id'],
                                               'name' => $data['name'] ?? "Package",
                                               'main_units_number' => $data['main_units_number'] ?? null,
                                               'package_count' => $data['package_count'] ?? null,
                                               'weight_netto' => $data['weight_netto'] ?? null,
                                               'weight_brutto' => $data['weight_brutto'] ?? null,
                                               'height' => $data['height'] ?? null,
                                               'width' => $data['width'] ?? null,
                                               'length' => $data['length'] ?? null,
                                               'parent_id' => $parentUuid,
                                           ]);

                if (!empty($data['barcode'])) {
                    $package->barcode()->create(['barcode' => $data['barcode']]);
                }

                $packages[] = $package;
            }

            $this->buildChildLinks(collect($packages));

            return collect($packages);
        });
    }

    /**
     * Оновлення пакувань (створює, оновлює, видаляє зайві).
     */
    public function update(array $packagesData, string $goodsId): Collection
    {
        try {
            return DB::transaction(function () use ($packagesData, $goodsId) {
                $existingPackages = Package::where('goods_id', $goodsId)->get()->keyBy('id');

                $packages = collect();
                $indexToUuid = [];

                foreach ($packagesData as &$item) {
                    $isExisting = !empty($item['uuid']) && $item['uuid'] !== 'false';

                    if ($isExisting) {
                        $uuid = $item['uuid'];

                        if (!isset($existingPackages[$uuid])) {
                            throw new \Exception("Package with UUID {$uuid} not found");
                        }
                    } else {
                        $uuid = (string)Str::uuid();
                    }

                    $item['real_id'] = $uuid;

                    $frontendId = $item['id'] ?? $item['uuid'];
                    $indexToUuid[$frontendId] = $uuid;
                }
                unset($item);

                foreach ($packagesData as $item) {
                    $barcode = $item['barcode'] ?? null;
                    unset($item['barcode']);

                    $item['goods_id'] = $goodsId;
                    $isExisting = !empty($item['uuid']) && $item['uuid'] !== 'false';

                    $frontendId = $item['id'] ?? $item['uuid'];
                    unset($item['uuid']);

                    $item['parent_id'] = !empty($item['parent_id'])
                        ? ($indexToUuid[$item['parent_id']] ?? null)
                        : null;

                    $item['id'] = $item['real_id'];
                    unset($item['real_id']);

                    if ($isExisting && isset($existingPackages[$item['id']])) {
                        $package = $existingPackages[$item['id']];
                        $package->update($item);
                        unset($existingPackages[$package->id]);
                    } else {
                        $package = Package::create($item);
                    }

                    if ($barcode) {
                        $package->barcode()->updateOrCreate([], ['barcode' => $barcode]);
                    }

                    $packages->push($package);
                }

                if ($existingPackages->isNotEmpty()) {
                    $toDeleteIds = $existingPackages->keys();

                    $blocked = DB::table('leftovers')
                        ->select('package_id', DB::raw('COUNT(*) as cnt'))
                        ->whereIn('package_id', $toDeleteIds)
                        ->groupBy('package_id')
                        ->pluck('cnt', 'package_id');

                    $hardDeletable = $toDeleteIds->reject(fn($id) => isset($blocked[$id]))->values();

                    if ($hardDeletable->isNotEmpty()) {
                        Package::whereIn('id', $hardDeletable)->delete();
                    }

                    if ($blocked->isNotEmpty()) {
                        \Log::warning('Package delete blocked by leftovers', [
                            'goods_id' => $goodsId,
                            'blocked' => $blocked->toArray(),
                        ]);

                    }
                }

                $this->buildChildLinks($packages->unique('id')->values());

                return $packages->values();
            });
        } catch (\Throwable $e) {
            \Log::error('PackageService.update failed', [
                'goods_id' => $goodsId,
                'error' => $e->getMessage(),
                'trace' => $e->getTrace(),
                'payload' => $packagesData,
            ]);

            throw $e;
        }
    }
    /**
     * Побудова child_id для кожного пакету.
     * Для кожного елемента, який має parent_id, його parent отримає child_id.
     */
    private function buildChildLinks(Collection $packages): void
    {
        $map = $packages->keyBy('id');

        foreach ($packages as $pkg) {
            if ($pkg->parent_id && isset($map[$pkg->parent_id])) {
                $parent = $map[$pkg->parent_id];
                $parent->update(['child_id' => $pkg->id]);
            }
        }
    }
}

