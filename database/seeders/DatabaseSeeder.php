<?php

namespace Database\Seeders;

use Database\Seeders\Common\CountrySeeder;
use Database\Seeders\Company\CompanyCategorySeeder;
use Database\Seeders\Company\CompanyTypeSeeder;
use Database\Seeders\Company\LegalTypeSeeder;
use Database\Seeders\Company\StatusSeeder;
use Database\Seeders\Container\ContainerTypeSeeder;
use Database\Seeders\Document\DoctypeFieldSeeder;
use Database\Seeders\Document\DoctypeStructureSeeder;
use Database\Seeders\Document\DocumentStatusSeeder;
use Database\Seeders\Document\DocumentTypeSeeder;
use Database\Seeders\Document\DocumentTypeStatusSeeder;
use Database\Seeders\Register\RegisterStatusSeeder;
use Database\Seeders\Service\ServiceCategorySeeder;
use Database\Seeders\SKU\MeasurmentUnitSeeder;
use Database\Seeders\SKU\PackageTypeSeeder;
use Database\Seeders\SKU\SKUCategorySeeder;
use Database\Seeders\Storage\CellStatusSeeder;
use Database\Seeders\Storage\StorageTypeSeeder;
use Database\Seeders\Storage\WarehouseERPSeeder;
use Database\Seeders\Storage\WarehouseTypeSeeder;
use Database\Seeders\Task\TaskTypeSeeder;
use Database\Seeders\Transport\AdditionalEquipmentBrandSeeder;
use Database\Seeders\Transport\AdditionalEquipmentModelSeeder;
use Database\Seeders\Transport\AdditionalEquipmentTypeSeeder;
use Database\Seeders\Transport\AdrSeeder;
use Database\Seeders\Transport\CargoTypeSeeder;
use Database\Seeders\Transport\DeliveryTypeSeeder;
use Database\Seeders\Transport\DownloadZonesSeeder;
use Database\Seeders\Transport\TransportBrandSeeder;
use Database\Seeders\Transport\TransportCategorySeeder;
use Database\Seeders\Transport\TransportDownloadSeeder;
use Database\Seeders\Transport\TransportModelSeeder;
use Database\Seeders\Transport\TransportTypeSeeder;
use Database\Seeders\TransportPlanning\TransportPlanningFailureStatusesSeeder;
use Database\Seeders\TransportPlanning\TransportPlanningStatusesSeeder;
use Database\Seeders\User\ExceptionTypeSeeder;
use Database\Seeders\User\PositionSeeder;
use Database\Seeders\User\RoleAndPermissionsSeeder;
use Database\Seeders\User\StatusSeeder as UserStatusSeeder;
use Database\Seeders\Warehouse\CellSeeder;
use Database\Seeders\Warehouse\WarehouseTypeCleanupSeeder;
use Database\Seeders\Warehouse\ZoneSubtypesSeeder;
use Database\Seeders\Warehouse\ZoneTypesSeeder;
use Database\Seeders\Warehouse\ZoneTypeSubtypeSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StatusSeeder::class,
            CellStatusSeeder::class,
            StorageTypeSeeder::class,
            AdditionalEquipmentBrandSeeder::class,
            AdditionalEquipmentModelSeeder::class,
            TransportBrandSeeder::class,
            TransportCategorySeeder::class,
            TransportTypeSeeder::class,
            TransportModelSeeder::class,
            RoleAndPermissionsSeeder::class,
            UserStatusSeeder::class,
            AdrSeeder::class,
            CountrySeeder::class,
            ExceptionTypeSeeder::class,
            TransportDownloadSeeder::class,
            PositionSeeder::class,
            CompanyTypeSeeder::class,
//            LegalTypeSeeder::class,
            WarehouseTypeSeeder::class,
            MeasurmentUnitSeeder::class,
            SKUCategorySeeder::class,
//            PackageTypeSeeder::class,
            DocumentTypeStatusSeeder::class,
            DoctypeFieldSeeder::class,
            DownloadZonesSeeder::class,
            RegisterStatusSeeder::class,
            DocumentStatusSeeder::class,
            ServiceCategorySeeder::class,
            ContainerTypeSeeder::class,
            ServiceCategorySeeder::class,
            TransportPlanningStatusesSeeder::class,
            TransportPlanningFailureStatusesSeeder::class,
            AdditionalEquipmentTypeSeeder::class,
            CompanyCategorySeeder::class,
            CargoTypeSeeder::class,
            DeliveryTypeSeeder::class,
            DoctypeStructureSeeder::class,
            TaskTypeSeeder::class,
            CellSeeder::class,
            DLegalTypesSeeder::class,
            ZoneTypesSeeder::class,
            ZoneSubtypesSeeder::class,
            ZoneTypeSubtypeSeeder::class,
            DPackageTypesSeeder::class,
            WarehouseTypeCleanupSeeder::class
        ]);
    }
}
