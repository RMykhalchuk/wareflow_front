<?php

namespace App\Factories;

use App\Enums\Currency;

use App\Models\Dictionaries\AdditionalEquipmentBrand;
use App\Models\Dictionaries\AdditionalEquipmentModel;
use App\Models\Dictionaries\AdditionalEquipmentType;
use App\Models\Dictionaries\Adr;
use App\Models\Dictionaries\CargoType;
use App\Models\Dictionaries\CellStatus;
use App\Models\Dictionaries\CompanyCategory;
use App\Models\Dictionaries\CompanyStatus;
use App\Models\Dictionaries\DownloadZone;
use App\Models\Dictionaries\GoodsCategory;
use App\Models\Dictionaries\LegalType;
use App\Models\Dictionaries\MeasurementUnit;
use App\Models\Dictionaries\PackageType;
use App\Models\Dictionaries\Position;
use App\Models\Dictionaries\ServiceCategories;
use App\Models\Dictionaries\StorageType;
use App\Models\Dictionaries\TransportBrand;
use App\Models\Dictionaries\TransportCategory;
use App\Models\Dictionaries\TransportDownload;
use App\Models\Dictionaries\TransportModel;
use App\Models\Dictionaries\TransportType;
use App\Models\Dictionaries\WarehouseType;
use App\Models\Entities\Address\Country;
use App\Models\Entities\Address\Settlement;
use App\Models\Entities\Address\Street;
use App\Models\Entities\Container\Container;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Container\ContainerType;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Location;
use App\Models\Entities\Package;
use App\Models\Entities\Task\TaskType;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\Row;
use App\Models\Entities\WarehouseComponents\Sector;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\Entities\WarehouseComponents\WarehouseErp;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use App\Services\Web\Auth\AuthContextService;


final class DictionaryFactory
{
    public static function adr(): Adr
    {
        return new Adr();
    }

    public static function cell_status(): CellStatus
    {
        return new CellStatus();
    }

    public static function company_status(): CompanyStatus
    {
        return new CompanyStatus();
    }

    public static function country(): Country
    {
        return new Country();
    }

    public static function download_zone(): DownloadZone
    {
        return new DownloadZone();
    }

    public static function measurement_unit(): MeasurementUnit
    {
        return new MeasurementUnit();
    }

    public static function package_type(): PackageType
    {
        return new PackageType();
    }

    public static function position(): Position
    {
        return new Position();
    }


    public static function settlement(bool $inTable): Settlement|null
    {
        if ($inTable) {
            return new Settlement();
        }
        return null;
    }

    public static function street(bool $inTable): Street|null
    {
        if ($inTable) {
            return new Street();
        }
        return null;
    }

    public static function storage_type(): StorageType
    {
        return new StorageType();
    }

    public static function transport_brand(): TransportBrand
    {
        return new TransportBrand();
    }

    public static function transport_download(): TransportDownload
    {
        return new TransportDownload();
    }

    public static function transport_kind(): TransportCategory
    {
        return new TransportCategory();
    }

    public static function transport_type(): TransportType
    {
        return new TransportType();
    }

    public static function warehouse_type(): WarehouseType
    {
        return new WarehouseType();
    }

    /**
     * @return (int|string)[][]
     *
     * @psalm-return list{array{id: 1|2|3, name: 'EUR'|'UAH'|'USD'}, array{id: 1|2|3, name: 'EUR'|'UAH'|'USD'}, array{id: 1|2|3, name: 'EUR'|'UAH'|'USD'}}
     */
    public static function currencies(): array
    {
        return array_map(function ($item) {
            return [
                'id' => $item->value,
                'name' => $item->name
            ];
        }, Currency::cases());
    }


    public static function legal_type(): LegalType
    {
        return (new LegalType());
    }

    public static function warehouse_erp(): WarehouseErp
    {
        return (new WarehouseErp());
    }

    public static function transport_model(): TransportModel
    {
        return (new TransportModel());
    }

    public static function additional_equipment_model(): AdditionalEquipmentModel
    {
        return (new AdditionalEquipmentModel());
    }

    public static function additional_equipment_brand(): AdditionalEquipmentBrand
    {
        return (new AdditionalEquipmentBrand());
    }

    public static function additional_equipment_type(): AdditionalEquipmentType
    {
        return (new AdditionalEquipmentType());
    }


    public static function container_type(): ContainerType
    {
        return (new ContainerType());
    }

    public static function container(): Container
    {
        return (new Container());
    }

    public static function location(): Location
    {
        return (new Location());
    }


    public static function service_category(): ServiceCategories
    {
        return (new ServiceCategories());
    }

    public static function goods_category(): GoodsCategory
    {
        return (new GoodsCategory());
    }

    public static function company_category(): CompanyCategory
    {
        return (new CompanyCategory());
    }

    public static function cargo_type(): CargoType
    {
        return new CargoType();
    }

    public static function delivery_type(): void
    {
        // return new CargoType();
    }

    /**
     * @return (int|string)[][]
     *
     * @psalm-return list{array{id: 1, key: 'tn', name: 'ТТ'}, array{id: 2, key: 'transport_request', name: 'Запит на транспорт'}}
     */
    public static function basis_for_ttn(): array
    {
        return [
            ['id' => 1, 'key' => 'tn', 'name' => 'ТТ'],
            ['id' => 2, 'key' => 'transport_request', 'name' => 'Запит на транспорт']
        ];
    }

    public static function warehouses_erp()
    {
        return new WarehouseErp();
    }

    public static function zones()
    {
        return WarehouseZone::query()
            ->when(request()->filled('warehouse_id'), fn($q)
                => $q->where('warehouse_id', request('warehouse_id'))
            );
    }

    public static function sectors()
    {
        return Sector::query()
            ->when(request()->filled('zone_id'), fn($q)
                => $q->where('zone_id', trim(request('zone_id')))
            );
    }

    public static function rows()
    {
        return Row::query()
            ->when(request()->filled('sector_id'), fn($q)
                => $q->where('sector_id', request('sector_id'))
            );
    }

    public static function cells()
    {
        return Cell::query()
            ->when(request()->filled('row_id'), fn($q)
                => $q->where('model_id', request('row_id'))
            );
    }

    public static function warehouses()
    {
        return Warehouse::query();
    }

    public static function packages()
    {
        return Package::query()
            ->when(request()->filled('goods_id'), fn($q)
                => $q->where('goods_id', request('goods_id'))
            );
    }


    public static function goods(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Goods::with(
            [
                'measurement_unit' => function ($q) {
                    $q->select('id', 'name');
                },
                'manufacturer_country' => function ($q) {
                    $q->select('id', 'key', 'name');
                },
                'brandCompany' => fn ($q) => $q->addName(),
                'providerCompany' => fn ($q) => $q->addName(),
                'manufacturerCompany' => fn ($q) => $q->addName(),
            ]);


        if (request()->filled('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        return $query;
    }


    public static function container_registers()
    {
        return new ContainerRegister();
    }

    public static function task_types()
    {
        return TaskType::where('is_system', 1)->orWhere('creator_company_id', app(AuthContextService::class)->getCompanyId());
    }

    public static function cells_by_warehouse()
    {
        $warehouseId = $_GET['warehouse_id'];

        $cell = Cell::where(function ($q) use ($warehouseId) {
            // parent = Zone
            $q->whereHasMorph(
                'parent',
                [WarehouseZone::class],
                function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                }
            );

            // parent = Sector
            $q->orWhereHasMorph(
                'parent',
                [Sector::class],
                function ($query) use ($warehouseId) {
                    $query->whereHas('zone', function ($zone) use ($warehouseId) {
                        $zone->where('warehouse_id', $warehouseId);
                    });
                }
            );

            // parent = Row
            $q->orWhereHasMorph(
                'parent',
                [Row::class],
                function ($query) use ($warehouseId) {
                    $query->whereHas('sector.zone', function ($zone) use ($warehouseId) {
                        $zone->where('warehouse_id', $warehouseId);
                    });
                }
            );
        });

        return $cell;
    }

    public static function cells_by_warehouse_and_receiving_type()
    {
        $warehouseId = request()->get('warehouse_id');

        if (!$warehouseId) {
            return Cell::query()->whereRaw('0 = 1');
        }

        $zoneIds = WarehouseZone::where('warehouse_id', $warehouseId)
            ->where('zone_type', 2)
            ->where('zone_subtype', 5)
            ->pluck('id');


        $sectorIds = Sector::whereIn('zone_id', $zoneIds)->pluck('id');
        $rowIds    = Row::whereIn('sector_id', $sectorIds)->pluck('id');

        $cells = Cell::with([
            'parent' => function ($morph) {
                $morph->morphWith([
                    WarehouseZone::class => ['warehouse.location'],
                    Sector::class => ['zone.warehouse.location'],
                    Row::class => ['sector.zone.warehouse.location'],
                ]);
            }
        ])->where(function ($query) use ($zoneIds, $sectorIds, $rowIds) {
            $query->where(function ($q) use ($zoneIds) {
                $q->where('parent_type', 'zone')->whereIn('model_id', $zoneIds);
            })->orWhere(function ($q) use ($sectorIds) {
                $q->where('parent_type', 'sector')->whereIn('model_id', $sectorIds);
            })->orWhere(function ($q) use ($rowIds) {
                $q->where('parent_type', 'row')->whereIn('model_id', $rowIds);
            });
        });

        return $cells;
    }


    public static function all_cells()
    {
        $companyId = app(AuthContextService::class)->getCompanyId();

        $cell = Cell::where(function ($q) use ($companyId) {
            // parent = Zone
            $q->whereHasMorph(
                'parent',
                [WarehouseZone::class],
                function ($query) use ($companyId) {
                    $query->whereHas('warehouse', function ($warehouse) use ($companyId) {
                        $warehouse->where('creator_company_id', $companyId);
                    });
                }
            );

            // parent = Sector
            $q->orWhereHasMorph(
                'parent',
                [Sector::class],
                function ($query) use ($companyId) {
                    $query->whereHas('zone.warehouse', function ($warehouse) use ($companyId) {
                        $warehouse->where('creator_company_id', $companyId);
                    });
                }
            );

            // parent = Row
            $q->orWhereHasMorph(
                'parent',
                [Row::class],
                function ($query) use ($companyId) {
                    $query->whereHas('sector.zone.warehouse', function ($warehouse) use ($companyId) {
                        $warehouse->where('creator_company_id', $companyId);
                    });
                }
            );
        });

        $cell->select(['id', 'code']);

        return $cell;
    }
}
