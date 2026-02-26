<?php

namespace App\Services\Web\Goods;

use App\Http\Requests\Web\Goods\GoodsKitsItemsRequest;
use App\Http\Requests\Api\Goods\GoodsRequest;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\LeftoverErp\LeftoverErp;
use App\Models\Dictionaries\Adr;
use App\Models\Dictionaries\GoodsCategory;
use App\Models\Dictionaries\MeasurementUnit;
use App\Models\Dictionaries\PackageType;
use App\Models\Entities\Address\Country;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Goods\GoodsKitItem;
use App\Models\Entities\System\Workspace;
use App\Services\Web\Goods\Package\PackageServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;


class GoodsService implements GoodsServiceInterface
{
    public function getAllGoods(): array
    {
        $goods = Goods::all();
        return compact('goods');
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function list(array $params): LengthAwarePaginator
    {
        $q = isset($params['q']) ? (string)$params['q'] : '';
        $perPage = isset($params['per_page']) ? (int)$params['per_page'] : 20;
        $perPage = $perPage > 0 ? min($perPage, 100) : 20;

        $like = DB::connection()->getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';

        return Goods::query()
            ->with([
                       'barcodes' => function ($b) {
                           $b->select(['id', 'entity_id', 'entity_type', 'barcode']);
                       },
                       'packages' => function ($p) {
                           $p->select(['id', 'goods_id', 'name', 'main_units_number']);
                       },
                       'measurement_unit' => function ($m) {
                           $m->select(['id', 'name']);
                       },
                   ])
            ->when($q !== '', function ($query) use ($q, $like) {
                $query->where(function ($w) use ($q, $like) {
                    $w->where('name', $like, "%{$q}%")
                        ->orWhere('brand', $like, "%{$q}%")
                        ->orWhere('manufacturer', $like, "%{$q}%")
                        ->orWhereHas('barcodes', function ($b) use ($q, $like) {
                            $b->where('barcode', $like, "%{$q}%");
                        });
                });
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function prepareCreateData(): array
    {
        $categories = GoodsCategory::all();
        $measurementUnits = MeasurementUnit::all();
        $companies = Company::whereHas('companiesInWorkspace', function ($query) {
            $query->where('workspace_id', Workspace::current());
        })->orWhere('workspace_id', Workspace::current())->get();
        $adr = Adr::all();
        $countries = Country::all();
        $packageTypes = PackageType::all();

        return compact(
            'categories',
            'measurementUnits',
            'companies',
            'adr',
            'countries',
            'packageTypes'
        );
    }

    public function prepareEditData(Goods $sku): array
    {
        $sku->load(
            [
                'category',
                'packages' => function ($query) {
                    $query->with(['barcode', 'type'])->withCount('leftovers')->withCount('income_document_leftovers');
                },
                'barcodes',
                'goodsKitItems.packages' => function ($query) {
                    $query->withCount('leftovers')->withCount('income_document_leftovers');
                },
                'goodsKitItems.goods'
            ]);

        $categories = GoodsCategory::all();
        $measurementUnits = MeasurementUnit::all();
        $companies = Company::whereHas('companiesInWorkspace', function ($query) {
            $query->where('workspace_id', Workspace::current());
        })
            ->orWhere('workspace_id', Workspace::current())
            ->select(['companies.*'])
            ->addName()
            ->get();
        $adrs = Adr::all();
        $countries = Country::all();
        $packageTypes = PackageType::all();

        return compact(
            'categories',
            'measurementUnits',
            'companies',
            'adrs',
            'countries',
            'packageTypes',
            'sku',
        );
    }

    public function prepareShowData(Goods $sku): array
    {
        $sku->load(
            [
                'category',
                'manufacturer_country',
                'adr',
                'measurement_unit',
                'packages.barcode',
                'packages.type',
                'barcodes',
                'goodsKitItems.goods',
                'goodsKitItems.packages',
            ]);
        $sku->loadSum('leftovers as leftovers_wms_total', 'quantity');
        $sku->loadSum('leftoversErp as leftovers_erp_total', 'quantity');

        return compact('sku');
    }

    public function getSkuByCategory(int $id): AnonymousResourceCollection
    {
        $sku = Goods::where('category_id', $id)->get();

        return $sku;
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $goodsData = $request->except(['_token', 'barcodes', 'packages', 'image']);

            $goods = new Goods();
            $goods->fill($goodsData);
            $goods->save();

            $goods->storeImage($request);

            if ($packages = $request->get('packages')) {
                $packageService = resolve(PackageServiceInterface::class);
                $packageService->store($packages, $goods['id']);
            }

            if ($barcodes = $request->get('barcodes')) {
                foreach ($barcodes as $barcode) {
                    $goods->barcodes()->create(['barcode' => $barcode]);
                }
            }

            DB::commit();

            return $goods->id;
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to store goods', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all(),
            ]);

            abort(500, 'Unable to create goods');
        }
    }

    /**
     * @throws Throwable
     */
    public function storeKits(Request $request)
    {
        DB::beginTransaction();

        try {
            $goodsData = $request->except(['_token', 'barcodes', 'packages', 'image', 'goods']);

            $goods = new Goods();
            $goodsData["is_kit"] = true;
            $goods->fill($goodsData);
            $goods->save();

            if ($packages = $request->get('packages')) {
                $packageService = resolve(PackageServiceInterface::class);
                $packageService->store($packages, $goods['id']);
            }

            if ($barcodes = $request->get('barcodes')) {
                foreach ($barcodes as $barcode) {
                    $goods->barcodes()->create(['barcode' => $barcode]);
                }
            }
            if ($goods_list = $request->get('goods')) {
                foreach ($goods_list as &$item) {
                    $item['goods_parent_id'] = $goods->id;
                    GoodsKitItem::create($item);
                }
            }
            $goods->storeImage($request);

            DB::commit();

            return $goods->id;
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to store goods', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all(),
            ]);

            abort(500, 'Unable to create goods');
        }
    }

    public function update(Goods $goods, Request $request): void
    {
        try {
            $image_type = false;

            if ($request->hasFile('image')) {
                $image_type = $request?->file('image')?->extension();
                $goods->storeImage($request);
            }

            $request->files->remove('image');
            $request->request->remove('image');

            $goodsData = $request->except(['image', 'barcodes', 'packages', '_token', '_method']);

            if ($image_type) {
                $goodsData['img_type'] = $image_type;
            }

            if ($packages = $request->get('packages')) {
                $packageService = resolve(PackageServiceInterface::class);
                $packageService->update($packages, $goods['id']);
            }

            $goods->fill($goodsData)->save();

            if ($barcodes = $request->input('barcodes')) {
                $barcodes = array_filter($barcodes, fn ($b) => $b !== null && $b !== '');

                $goods->barcodes()->whereNotIn('barcode', $barcodes)->delete();

                foreach ($barcodes as $barcode) {
                    $goods->barcodes()->firstOrCreate(['barcode' => $barcode]);
                }
            }

//            if ($goods_list = $request->get('goods')) {
//                foreach ($goods_list as &$item) {
//                    $item['goods_parent_id'] = $goods->id;
////                     GoodsKitItem::firstOrCreate($item);
//                }
//            }

        } catch (\Throwable $e) {
            \Log::error('Failed to update goods', [
                'error' => $e->getMessage(),
                'trace' => $e->getTrace(),
                'goods_id' => $goods->id,
                'data' => $request->except(['image']),
            ]);

            abort(500, 'Unable to update goods');
        }
    }

    public function updateKits(Goods $goods, Request $request): void
    {
      //  try {
            $image_type = false;

            if ($request->hasFile('image')) {
                $image_type = $request?->file('image')?->extension();
                $goods->storeImage($request);
            }

            $request->files->remove('image');
            $request->request->remove('image');

            $goodsData = $request->safe()->except(['image', 'barcodes', 'packages', '_token', '_method', 'goods']);

            if ($image_type) {
                $goodsData['img_type'] = $image_type;
            }

            if ($packages = $request->get('packages')) {
                $packageService = resolve(PackageServiceInterface::class);
                $packageService->update($packages, $goods['id']);
            }

            $goods->fill($goodsData)->save();

            if ($goodsList = $request->get('goods')) {
                $this->updateKitItems($goods, $goodsList);
            } else {
                $goods->goodsKitItems()->delete();
            }

            if ($barcodes = $request->input('barcodes')) {
                $barcodes = array_filter($barcodes, fn ($b) => $b !== null && $b !== '');

                $goods->barcodes()->whereNotIn('barcode', $barcodes)->delete();

                foreach ($barcodes as $barcode) {
                    $goods->barcodes()->firstOrCreate(['barcode' => $barcode]);
                }
            }
//        } catch (\Throwable $e) {
//            \Log::error('Failed to update goods', [
//                'error' => $e->getMessage(),
//                'trace' => $e->getTrace(),
//                'goods_id' => $goods->id,
//                'data' => $request->except(['image']),
//            ]);
//
//            abort(500, 'Unable to update goods');
//        }
    }

    protected function updateKitItems(Goods $goods, array $newItems): void
    {
        $existingItems = $goods->goodsKitItems;

        $newGoodsIds = collect($newItems)->pluck('goods_id')->toArray();

        //(DELETE) ---
        $itemsToDelete = $existingItems->filter(function ($item) use ($newGoodsIds) {
            return !in_array($item->goods_id, $newGoodsIds);
        });

        $itemsToDelete->each->delete();


        //(CREATE / UPDATE) ---
        foreach ($newItems as $itemData) {
            $itemData['goods_parent_id'] = $goods->id;

            $existingItem = $existingItems->firstWhere('goods_id', $itemData['goods_id']);
            if ($existingItem) {
                $existingItem->update($itemData);
            } else {
                GoodsKitItem::create($itemData);
            }
        }
    }

    /**
     * @param string $goodsId
     * @return array
     */
    public function getExpirationOptions(string $goodsId): array
    {
        $goods = Goods::query()
            ->select(['id', 'expiration_date'])
            ->find($goodsId);

        if (!$goods || !is_array($goods->expiration_date)) {
            return [];
        }

        return collect($goods->expiration_date)
            ->filter(fn($v) => filled($v))
            ->map(fn($v)
                => [
                'id' => (string)trim($v),
                'name' => (string)trim($v),
            ])
            ->unique('id')
            ->values()
            ->all();
    }
}
