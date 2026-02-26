<?php

namespace App\Providers;

use App\Helpers\PaginationMacro;
use App\Interfaces\AvatarInterface;
use App\Interfaces\StoreFileInterface;
use App\Interfaces\StoreImageInterface;
use App\Models\Entities\Company\LegalCompany;
use App\Models\Entities\Company\PhysicalCompany;
use App\Models\Entities\Container\Container;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\DocumentType;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Leftover\LeftoverToContainerRegister;
use App\Models\Entities\LeftoverErp\LeftoverErp;
use App\Models\Entities\Location;
use App\Models\Entities\Package;
use App\Models\Entities\Task\Task;
use App\Models\Entities\Transport\AdditionalEquipment;
use App\Models\Entities\Transport\Transport;
use App\Models\Entities\User\UserWorkingData;
use App\Models\Entities\WarehouseComponents\Row;
use App\Models\Entities\WarehouseComponents\Sector;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use App\Models\User;
use App\Services\Api\Category\CategoryServiceInterface;
use App\Services\Terminal\Completing\PickingService;
use App\Services\Terminal\Completing\PickingServiceInterface;
use App\Services\Terminal\InternalMovement\Manual\ManualMovementService;
use App\Services\Terminal\InternalMovement\Manual\ManualMovementServiceInterface;
use App\Services\Terminal\Inventory\InventoryItemServiceInterface;
use App\Services\Terminal\Inventory\InventoryLeftoverServiceInterface;
use App\Services\Terminal\Inventory\InventoryManualServiceInterface;
use App\Services\Terminal\Inventory\InventoryServiceInterface;
use App\Services\Web\AdditionalEquipment\AdditionalEquipmentService;
use App\Services\Web\AdditionalEquipment\AdditionalEquipmentServiceInterface;
use App\Services\Web\Auth\AuthContextService;
use App\Services\Web\Auth\GuardContext;
use App\Services\Web\Auth\Register\AuthRegisterService;
use App\Services\Web\Auth\Register\AuthRegisterServiceInterface;
use App\Services\Web\Bookmark\BookmarkService;
use App\Services\Web\Bookmark\BookmarkServiceInterface;
use App\Services\Web\Category\CategoryService;
use App\Services\Web\Company\CompanyService;
use App\Services\Web\Company\CompanyServiceInterface;
use App\Services\Web\Container\ContainerService;
use App\Services\Web\Container\ContainerServiceInterface;
use App\Services\Web\ContainerRegister\ContainerRegisterService;
use App\Services\Web\ContainerRegister\ContainerRegisterServiceInterface;
use App\Services\Web\Contract\ContractService;
use App\Services\Web\Contract\ContractServiceInterface;
use App\Services\Web\Document\DocumentService;
use App\Services\Web\Document\DocumentServiceInterface;
use App\Services\Web\Document\Income\IncomeDocumentInterface;
use App\Services\Web\Document\Income\IncomeDocumentService;
use App\Services\Web\Document\IncomeLeftover\IncomeLeftoverInterface;
use App\Services\Web\Document\Outcome\OutcomeDocumentService;
use App\Services\Web\Document\Outcome\OutcomeDocumentServiceInterface;
use App\Services\Web\Document\IncomeLeftover\IncomeLeftoverService;
use App\Services\Web\Document\OutcomeLeftover\OutcomeLeftoverInterface;
use App\Services\Web\Document\OutcomeLeftover\OutcomeLeftoverService;
use App\Services\Web\Document\ReserveLeftover\ReserveLeftoverInterface;
use App\Services\Web\Document\ReserveLeftover\ReserveLeftoverService;
use App\Services\Web\DocumentType\DocumentTypeService;
use App\Services\Web\DocumentType\DocumentTypeServiceInterface;
use App\Services\Web\File\FileService;
use App\Services\Web\File\FileServiceInterface;
use App\Services\Web\File\StoreFile;
use App\Services\Web\File\StoreImage;
use App\Services\Web\Goods\GoodsService;
use App\Services\Web\Goods\GoodsServiceInterface;
use App\Services\Web\Goods\Package\PackageService;
use App\Services\Web\Goods\Package\PackageServiceInterface;
use App\Services\Web\Inventory\InventoryItemService;
use App\Services\Web\Inventory\InventoryLeftoverService;
use App\Services\Web\Inventory\InventoryManualService;
use App\Services\Web\Inventory\InventoryService;
use App\Services\Web\Leftover\LeftoverService;
use App\Services\Web\Leftover\LeftoverServiceInterface;
use App\Services\Web\LeftoverErp\LeftoverErpService;
use App\Services\Web\LeftoverErp\LeftoverErpServiceInterface;
use App\Services\Web\LeftoverToContainer\LeftoverToContainerRegisterService;
use App\Services\Web\LeftoverToContainer\LeftoverToContainerRegisterServiceInterface;
use App\Services\Web\Location\LocationService;
use App\Services\Web\Location\LocationServiceInterface;
use App\Services\Web\Registers\RegisterService;
use App\Services\Web\Registers\RegisterServiceInterface;
use App\Services\Web\Schedule\ScheduleService;
use App\Services\Web\Schedule\ScheduleServiceInterface;
use App\Services\Web\Table\TableService;
use App\Services\Web\Table\TableServiceInterface;
use App\Services\Web\Task\TaskService;
use App\Services\Web\Task\TaskServiceInterface;
use App\Services\Web\TaskItem\TaskItemService;
use App\Services\Web\TaskItem\TaskItemServiceInterface;
use App\Services\Web\Transport\TransportService;
use App\Services\Web\Transport\TransportServiceInterface;
use App\Services\Web\TransportPlanning\TransportPlanningService;
use App\Services\Web\TransportPlanning\TransportPlanningServiceInterface;
use App\Services\Web\User\Avatar;
use App\Services\Web\Warehouse\Cell\CellService;
use App\Services\Web\Warehouse\Cell\CellServiceInterface;
use App\Services\Web\Warehouse\Row\RowService;
use App\Services\Web\Warehouse\Row\RowServiceInterface;
use App\Services\Web\Warehouse\Sector\SectorService;
use App\Services\Web\Warehouse\Sector\SectorServiceInterface;
use App\Services\Web\Warehouse\WarehouseService;
use App\Services\Web\Warehouse\WarehouseServiceInterface;
use App\Services\Web\Warehouse\Zone\ZoneService;
use App\Services\Web\Warehouse\Zone\ZoneServiceInterface;
use App\Services\Web\Warehouse\Zone\ZoneTypeSubtypeService;
use App\Services\Web\Warehouse\Zone\ZoneTypeSubtypeServiceInterface;
use App\Services\Web\Workspace\WorkspaceService;
use App\Services\Web\Workspace\WorkspaceServiceInterface;
use App\Support\Blueprint;
use App\View\Components\BookmarkComponent;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    #[\Override]
    public function register()
    {
        $this->app->bind(AvatarInterface::class, Avatar::class);

        $this->app->bind(StoreImageInterface::class, StoreImage::class);

        $this->app->bind(StoreFileInterface::class, StoreFile::class);

        $this->app->singleton(AuthContextService::class);

        $this->app->singleton(GuardContext::class);

        $this->bindServices();

        $this->bindMorphs();
    }

    private function bindMorphs() : void
    {
        Relation::morphMap(
            [
                'legal_company' => LegalCompany::class,
                'physical_company' => PhysicalCompany::class,
                'warehouse' => Warehouse::class,
                'user' => User::class,
                'transport' => Transport::class,
                'container' => Container::class,
                'transport_equipment' => AdditionalEquipment::class,
                'goods' => Goods::class,
                'location' => Location::class,
                'container_register' => ContainerRegister::class,
                'goods_to_container_register' => LeftoverToContainerRegister::class,
                'zone' => WarehouseZone::class,
                'additional_equipment' => AdditionalEquipment::class,
                'document' => Document::class,
                'document_type' => DocumentType::class,
                'leftover' => Leftover::class,
                'leftover_erp' => LeftoverErp::class,
                'task' => Task::class,
                'user_working_data' => UserWorkingData::class,
                'row' => Row::class,
                'sector' => Sector::class,
                'package' => Package::class,
            ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // TODO remove in production case
        if (app()->environment(['production', 'staging', 'test'])) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }


        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });

        PaginationMacro::changePagination($this);
        Blade::component('bookmark', BookmarkComponent::class);

        Schema::blueprintResolver(function ($table, $callback) {
            return new Blueprint($table, $callback);
        });
    }

    private function bindServices()
    {
        $this->app->bind(LeftoverServiceInterface::class, LeftoverService::class);

        $this->app->bind(LeftoverToContainerRegisterServiceInterface::class, LeftoverToContainerRegisterService::class);

        $this->app->bind(BookmarkServiceInterface::class, BookmarkService::class);

        $this->app->bind(ContractServiceInterface::class, ContractService::class);

        $this->app->bind(CompanyServiceInterface::class, CompanyService::class);

        $this->app->bind(DocumentServiceInterface::class, DocumentService::class);

        $this->app->bind(DocumentTypeServiceInterface::class, DocumentTypeService::class);

        $this->app->bind(FileServiceInterface::class, FileService::class);

        $this->app->bind(GoodsServiceInterface::class, GoodsService::class);

        $this->app->bind(RegisterServiceInterface::class, RegisterService::class);

        $this->app->bind(ScheduleServiceInterface::class, ScheduleService::class);

        $this->app->bind(TableServiceInterface::class, TableService::class);

        $this->app->bind(TransportServiceInterface::class, TransportService::class);

        $this->app->bind(AdditionalEquipmentServiceInterface::class, AdditionalEquipmentService::class);

        $this->app->bind(TransportPlanningServiceInterface::class, TransportPlanningService::class);

        $this->app->bind(WarehouseServiceInterface::class, WarehouseService::class);

        $this->app->bind(WorkspaceServiceInterface::class, WorkspaceService::class);

        $this->app->bind(PackageServiceInterface::class, PackageService::class);

        $this->app->bind(LocationServiceInterface::class, LocationService::class);

        $this->app->bind(ContainerRegisterServiceInterface::class, ContainerRegisterService::class);

        $this->app->bind(ZoneServiceInterface::class, ZoneService::class);

        $this->app->bind(SectorServiceInterface::class, SectorService::class);

        $this->app->bind(RowServiceInterface::class, RowService::class);

        $this->app->bind(CellServiceInterface::class, CellService::class);

        $this->app->bind(AuthRegisterServiceInterface::class, AuthRegisterService::class);

        $this->app->bind(InventoryServiceInterface::class, InventoryService::class);

        $this->app->bind(InventoryItemServiceInterface::class, InventoryItemService::class);

        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);

        $this->app->bind(InventoryLeftoverServiceInterface::class, InventoryLeftoverService::class);

        $this->app->bind(ManualMovementServiceInterface::class, ManualMovementService::class);

        $this->app->bind(IncomeLeftoverInterface::class, IncomeLeftoverService::class);

        $this->app->bind(OutcomeLeftoverInterface::class, OutcomeLeftoverService::class);

        $this->app->bind(IncomeDocumentInterface::class, IncomeDocumentService::class);

        $this->app->bind(OutcomeDocumentServiceInterface::class, OutcomeDocumentService::class);

        $this->app->bind(InventoryManualServiceInterface::class, InventoryManualService::class);

        $this->app->bind(ContainerServiceInterface::class, ContainerService::class);

        $this->app->bind(TaskServiceInterface::class, TaskService::class);

        $this->app->bind(TaskItemServiceInterface::class, TaskItemService::class);

        $this->app->bind(LeftoverErpServiceInterface::class, LeftoverErpService::class);

        $this->app->bind(ZoneTypeSubtypeServiceInterface::class, ZoneTypeSubtypeService::class);

        $this->app->bind(ReserveLeftoverInterface::class, ReserveLeftoverService::class);

        $this->app->bind(PickingServiceInterface::class, PickingService::class);



    }
}
