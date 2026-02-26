<?php


use App\Http\Controllers\Web\AddressController;
use App\Http\Controllers\Web\Auth\FeedbackController;
use App\Http\Controllers\Web\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\RegisterController as RegistrationController;
use App\Http\Controllers\Web\Auth\ResetPasswordController;
use App\Http\Controllers\Web\Auth\VerificationCodeController;
use App\Http\Controllers\Web\BarcodeController;
use App\Http\Controllers\Web\BookmarkController;
use App\Http\Controllers\Web\Category\CategoryController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\Container\ContainerController;
use App\Http\Controllers\Web\Container\ContainerRegisterController;
use App\Http\Controllers\Web\ContractController;
use App\Http\Controllers\Web\DictionaryController;
use App\Http\Controllers\Web\FileController;
use App\Http\Controllers\Web\Goods\GoodsController;
use App\Http\Controllers\Web\Goods\GoodsMovementHistoryController;
use App\Http\Controllers\Web\Goods\GoodsTypeController;
use App\Http\Controllers\Web\Goods\PackageController;
use App\Http\Controllers\Web\IntegrationController;
use App\Http\Controllers\Web\InventoryController;
use App\Http\Controllers\Web\InvoiceController;
use App\Http\Controllers\Web\LanguageController;
use App\Http\Controllers\Web\LayoutController;
use App\Http\Controllers\Web\Leftover\LeftoverController;
use App\Http\Controllers\Web\Leftover\LeftoverToContainerController;
use App\Http\Controllers\Web\LeftoverErpController;
use App\Http\Controllers\Web\LocationController;
use App\Http\Controllers\Web\OnboardingController;
use App\Http\Controllers\Web\PrintLabelController;
use App\Http\Controllers\Web\Registers\RegisterController;
use App\Http\Controllers\Web\Registers\RegisterStatusController;
use App\Http\Controllers\Web\RegulationController;
use App\Http\Controllers\Web\ResidueController;
use App\Http\Controllers\Web\StickerController;
use App\Http\Controllers\Web\TableController;
use App\Http\Controllers\Web\Task\TaskController;
use App\Http\Controllers\Web\Transport\TransportController;
use App\Http\Controllers\Web\Transport\TransportEquipmentController;
use App\Http\Controllers\Web\Transport\TransportPlanningController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ValidatorController;
use App\Http\Controllers\Web\Warehouse\CellController;
use App\Http\Controllers\Web\Warehouse\RowController;
use App\Http\Controllers\Web\Warehouse\ScheduleController;
use App\Http\Controllers\Web\Warehouse\SectorController;
use App\Http\Controllers\Web\Warehouse\WarehouseController;
use App\Http\Controllers\Web\Warehouse\WarehouseErpController;
use App\Http\Controllers\Web\Warehouse\ZoneController;
use App\Http\Controllers\Web\WorkspacesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Terminal\PackageController as TerminalPackageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// Для верстки
Route::get('/view', [LayoutController::class, 'index']);

$prefix = App\Http\Middleware\LocaleMiddleware::getLocale();

Route::group(['prefix' => $prefix], function () {

    Route::get('/', function () {
        $user = auth()->user();

        if ($user->workingData?->hasRole('user')) {
            return redirect()->route('leftovers.index');
        }

        return redirect()->route('user-board');
    })->middleware('auth')->name('main-page');

    Route::get('login', [LoginController::class, 'showLoginForm'])
        ->middleware('guest')
        ->name('login');

    Route::post('login', [LoginController::class, 'login'])
        ->middleware('guest');

    Route::post('logout', [LoginController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    Route::get('/files/{path}', [FileController::class, 'getFile'])
        ->where('path', '.*')
        ->name('file.get');

    Route::prefix('register')->controller(RegistrationController::class)->group(function () {
        Route::post('send-code', 'sendVerificationCode')->name('register.send-code');
        Route::post('register', 'register')->name('registration');
    });

    Route::prefix('verification')->controller(VerificationCodeController::class)->group(function () {
        Route::post('validate-code', 'validateCode')->name('verification.validate-code');
    });

    Route::prefix('password')->group(function () {
        Route::post('send-code', [ForgotPasswordController::class, 'sendVerificationCode'])->name('password.send-code');
        Route::post('reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
    });

    Route::post('contact-admin', [FeedbackController::class, 'contactWithAdmin'])->name('feedback.contact-with-admin');

    Route::prefix('address')->controller(AddressController::class)->group(function () {
        Route::get('/settlement', 'settlement')->name('address.settlement');
        Route::get('/street', 'street')->name('address.street');
    });

    Route::prefix('dictionary')->controller(DictionaryController::class)->middleware('company.context')->group(function () {
        //make exception for company
        Route::get('/company', 'getCompanyList')->name('dictionary.company');
        Route::get('/document-type', 'availableDoctypeDictionary');
        Route::get('/enums/{dictionary}', 'getEnumsList')->name('dictionary.enums');
        Route::get('/goods-expiration', 'getGoodsExpirationDictionary')
            ->name('dictionary.goods-expiration');
        Route::get('/zone_types', 'getZoneTypes')
            ->name('dictionary.zone-types');
        Route::get('/zone_subtypes', 'getZoneSubtypes')
            ->name('dictionary.zone-subtypes');
        Route::get('/{dictionary}', 'getDictionaryList')->name('dictionary.list');
    });

    Route::prefix('onboarding')->group(function () {
        Route::controller(OnboardingController::class)->group(function () {
            Route::get('/', 'index')->name('onboarding');
        });
    });

    Route::post('users/update/onboarding', [UserController::class, 'updateOnboarding']);
    Route::post('users/update/current-warehouse/{warehouseId}', [UserController::class, 'updateCurrentWarehouseWeb'])
        ->whereUuid('warehouseId')
        ->name('users.update.current-warehouse');
    Route::post('users/clear/current-warehouse', [UserController::class, 'clearCurrentWarehouseWeb'])
        ->name('users.clear.current-warehouse');


    Route::middleware('auth')->group(function () {
        include 'web/onboarding.php';

        //routes after onboarding complete
        Route::middleware(['onboarding-check', 'company.context'])->group(function () {
            Route::prefix('companies')->controller(CompanyController::class)->group(function () {
                Route::post('update-legal/{company}', 'updateLegalCompany')->name('update.legal-company');
                Route::post('update-physical/{company}', 'updatePhysicalCompany')->name('update.physical-company');
            });

            Route::prefix('validate')->controller(ValidatorController::class)->group(function () {
                Route::post('user-in-workspace', 'validateUserInWorkspace');
                Route::post('password-data', 'validatePasswordData');
                Route::post('pin-data', 'validatePinData');
            });

            Route::resource('/workspaces', WorkspacesController::class)->except(['create', 'store', 'show']);
            Route::prefix('workspaces')->group(function () {
                Route::controller(WorkspacesController::class)->group(function () {
                    Route::get('price', 'getPrice')->name('workspaces.price');
                    Route::get('list', 'getWorkspacesList')->name('workspaces.list');
                });
            });

            //<----------------------------------------------------------------------------------------------->
            //<----------------------------------------------------------------------------------------------->
            //<----------------------------------------------------------------------------------------------->


            //routes after workspace request was accepted
            Route::middleware('waiting-for-workspace')->group(function () {
                Route::prefix('users')->group(function () {
                    Route::controller(UserController::class)->group(function () {
                        Route::middleware('can:view-dictionaries')->group(function () {
                            Route::get('/all', 'users')->name('user-board');
                            Route::get('/update/{user}', 'update')->name('user.update');
                            Route::post('create', 'store')->name('user.store');
                            Route::get('create', 'create')->name('user.create');
                            Route::get('show/{user}', 'show')->name('user.show');
                            Route::delete('delete/{user}', 'destroy')->name('user.delete');
                            Route::get('filter', 'filter')->name('user.filter');
                        });

                        Route::post('account/update/{user}', 'updateData')->name('update-working-data');
                        Route::post('change-password/{user}', 'changePassword')->name('change-password');
                        Route::post('change-avatar/{user}', 'updateAvatar')->name('change-avatar');
                        Route::post('delete-avatar/{user}', 'deleteAvatar')->name('delete-avatar');
                        Route::get('change-temp-password', 'showChangeTempPasswordForm')->name('show-temp-password-form');
                        Route::post('change-temp-password', 'changeTempPassword')->name('update.temp.password');
                        Route::post('send-password', 'sendPassword')->name('user.send_password');
                    });
                    Route::controller(ScheduleController::class)->group(function () {
                        Route::post('create-schedule-pattern', 'store');
                        Route::get('schedule/update/{user}', 'editSchedule')->name('user.schedule-update');
                        Route::post('schedule/update/{user}', 'updateSchedule');
                        Route::post('warehouses/schedule/update/{warehouse}', 'updateWarehouseSchedule');
                    });
                });


                Route::prefix('companies')->controller(CompanyController::class)->group(function () {
                    Route::get('table/filter', 'filter')->name('company.filter');
                });
                Route::resource('/companies', CompanyController::class, ['except' => ['store', 'update']]);
                //TRANSPORT

                Route::resource('/transports', TransportController::class);
                Route::prefix('transports')->controller(TransportController::class)->group(function () {
                    Route::get('table/filter', 'filter')->name('transport.filter');
                    Route::get('model-by-brand/{transport_brand}', 'getModelByBrand');
                    Route::post('store-with-additional', 'storeWithAdditional');
                    Route::post('delete-image/{transport}', 'deleteImage')->name('transport.delete-image');
                    Route::put('update-with-additional/{transport}', 'updateWithAdditional');
                });

                Route::resource('/transport-equipments', TransportEquipmentController::class);
                Route::get('equipment-model-by-brand/{additional_equipment_brand}', [TransportEquipmentController::class, 'getModelByBrand']);
                Route::post('/transport-equipments/delete-image/{transport_equipment}', [TransportEquipmentController::class, 'deleteImage'])->name('transport-equipments.delete-image');
                Route::get('/transport-equipments/table/filter', [TransportEquipmentController::class, 'filter'])->name('transport-equipments.filter');
                //END TRANSPORT


                Route::prefix('warehouses')->controller(WarehouseController::class)->group(function () {
                    Route::get('table/filter', 'filter')->name('warehouse.filter');
                    Route::get('schedule/edit/{warehouse}', 'editSchedule')->name('warehouse.schedule-update');
                    Route::post('schedule/update/{warehouse}', 'updateSchedule');
                    Route::get('options', 'options')->name('warehouse.options');
                });
                Route::prefix('warehouses-erp')->controller(WarehouseErpController::class)->group(function () {
                    Route::get('table/filter', 'filter')->name('warehouse-erp.filter');
                });

                Route::get('dictionary/warehouses/options', [WarehouseController::class, 'options'])
                    ->name('dictionary.warehouse.options');

                //TODO: Поправити на нормальний шлях
                Route::get('warehouse-components', function () {
                    return view('warehouse.components');
                })->name('warehouse.components');

                Route::get('/warehouse/map', function () {
                    return view('warehouse.map.index');
                })->name('warehouse.map');

                Route::prefix('warehouses/sector')->controller(SectorController::class)->group(function () {
                    Route::get('table/filter', 'filter')->name('warehouse.filter');

                });

                Route::resource('warehouses', WarehouseController::class);
                Route::post('warehouses/schedule/pattern', [ScheduleController::class, 'storeSchedulePattern']);

                Route::resource('/warehouses.zones', ZoneController::class)->except(['create', 'edit', 'show']);
                Route::resource('/zones.sectors', SectorController::class)->except(['create', 'edit', 'show']);
                Route::resource('/sectors.rows', RowController::class)->except(['create', 'edit', 'show']);
                Route::resource('/rows.cells', CellController::class)->except(['create', 'edit', 'show']);
                Route::resource('/warehouse-erp', WarehouseErpController::class)->except(['create', 'edit', 'show']);

                Route::get('warehouses/{warehouse}/zones/{zone}/can-delete', [ZoneController::class, 'canDelete'])->name('zones.can-delete');
                Route::get('zones/{zone}/sectors/{sector}/can-delete', [SectorController::class, 'canDelete'])->name('sectors.can-delete');
                Route::get('sectors/{sector}/rows/{row}/can-delete', [RowController::class, 'canDelete'])->name('rows.can-delete');
                Route::get('rows/{row}/cells/{cell}/can-delete', [CellController::class, 'canDelete'])->name('cells.can-delete');

                Route::controller(GoodsController::class)
                    ->name('sku.')
                    ->prefix('sku')
                    ->group(function () {
                        Route::get('/kits/create', 'createKits')->name('kits');
                        Route::get('/kits/{sku}/edit', 'editKit')->name('kits.edit');
                        Route::post('/kits', 'storeKits')->name('kits.store');
                        Route::put('/kits/{sku}', 'updateKits')->name('kits.update');
                    });

                Route::resource('sku', GoodsController::class);
                Route::prefix('sku')->controller(GoodsController::class)->group(function () {
                    Route::get('get-by-category/{id}', 'getSkuByCategory')->name('sku.get-by-category');
                    Route::get('all-data/{sku}', 'getAllData')->name('sku.get-all-data');
                    Route::get('table/filter', 'filter')->name('sku.filter');
                    Route::get('table/{sku}/package-filter', 'packageFilter')->name('sku.package-filter');
                    Route::get('table/{sku}/barcode-filter', 'barcodeFilter')->name('sku.barcode-filter');
                });

                Route::prefix('sku')->controller(GoodsMovementHistoryController::class)->group(function () {
                    Route::get('{sku}/document-history/filter', 'filter')->name('sku.document-history.filter');
                });

                Route::apiResource('sku/package', PackageController::class);

                Route::prefix('sku')->controller(BarcodeController::class)->group(function () {
                    Route::post('barcode', 'create')->name('barcode.create');
                    Route::put('barcode/{barcode}', 'update')->name('barcode.update');
                    Route::delete('barcode/{barcode}', 'delete')->name('barcode.delete');
                });

                Route::prefix('table')->controller(TableController::class)->group(function () {
                    Route::get('{model}', 'index')->name('table.index');
                    Route::post('{model}', 'create')->name('table.create');
                    Route::put('{model}/{id}', 'update')->name('table.update');
                    Route::delete('{model}/{id}', 'delete')->name('table.delete');
                });

                Route::prefix('bookmarks')->controller(BookmarkController::class)->group(function () {
                    Route::get('find-by-key/{key}', 'findByKey');
                    Route::post('/', 'store');
                    Route::post('delete', 'deleteByKey');
                });

                Route::prefix('registers')->group(function () {
                    Route::controller(RegisterController::class)->group(function () {
                        Route::get('/storekeeper', 'storekeeper')->name('register.storekeeper');
                        Route::get('/guard', 'guardian')->name('register.guardian');
                        Route::get('/filter', 'filter')->name('register.filter');
                        Route::post('/', 'store')->name('register.store');
                        Route::put('/{register}', 'update')->name('register.update');
                        Route::get('/create', 'create')->name('register.create');
                        Route::get('/managers', 'getManagers')->name('register.managers');
                        Route::get('/storekeepers', 'getStorekeepers')->name('register.storekeepers');
                        Route::post('/page-by-record', 'getPageByRegister')->name('register.get-page');
                    });

                    Route::controller(RegisterStatusController::class)->group(function () {
                        Route::post('/status/register/{register}', 'registerStatus')->name('register.register-status');
                        Route::post('/status/apply/{register}', 'applyStatus')->name('register.apply-status');
                        Route::post('/status/launch/{register}', 'launchStatus')->name('register.launch-status');
                        Route::post('/status/released/{register}', 'releasedStatus')->name('register.released-status');
                        Route::post('/cancel/entrance/{register}', 'cancelEntrance')->name('register.cancel-entrance');
                    });
                });

                include 'web/document.php';

                Route::resource('/transport-planning', TransportPlanningController::class)->except(['update']);
                Route::prefix('transport-planning')->group(function () {
                    Route::controller(TransportPlanningController::class)->group(function () {
                        Route::get('table/filter', 'filter')->name('transport-planning.filter');
                        Route::get('table/transport-request-filter', 'transportRequestFilter')->name('transport-planning.transport-request-filter');

                        Route::get('list/{date}', 'listByDate')->name('transport-planning.list-by-date');
                        Route::get('table/goods-invoice-filter', 'goodsInvoicesFilter')->name('transport-planning.goods-invoices-filter');

                        Route::get('table/{id}/transport-request-filter', 'transportRequestByPlanningFilter')->name('transport-planning.transport-request-by-planning-filter');
                        Route::get('table/{id}/goods-invoice-filter', 'goodsInvoicesByPlanningFilter')->name('transport-planning.goods-invoices-by-planning-filter');
                        Route::get('documents', 'getDocuments')->name('transport-planning.documents');
                        Route::put('/status/{status}', 'updateStatus')->name('transport-planning.update-status');
                        Route::post('/status', 'addStatus')->name('transport-planning.add-status');
                        Route::post('/status/{status}/failure', 'addFailure')->name('transport-planning.add-failure');
                        Route::delete('/status/{status}', 'deleteStatus')->name('transport-planning.delete-status');
                    });
                });

                Route::resource('/integrations', IntegrationController::class)->only(['store', 'update', 'destroy']);

                Route::resource('/leftovers', LeftoverController::class)->only(['index', 'store']);

                Route::get('package/leftovers/{leftover}', [LeftoverController::class, 'getPackageInfo'])->name('leftovers.package');


                Route::prefix('leftovers')->group(function () {
                    Route::controller(LeftoverController::class)->group(function () {
                        Route::get('/table/filter', 'filter')->name('leftovers.filter');
                        Route::get('/table/filter-by-party', 'filterByParty')->name('leftovers.filter-by-party');
                        Route::get('/table/filter-by-package', 'filterByPartyAndPackage')->name('leftovers.filter-by-package');
                        Route::get('/table/leftover-by-cell/{cell_id}', 'filterByCell');

                        Route::get('/available/{goods}', 'getAvailableLeftovers')->name('leftovers.available');

                        Route::post('add/{document}', 'addByDocument')->name('leftovers.add-by-document');
                        Route::post('remove/{document}', 'removeByDocument')->name('leftovers.remove-by-document');
                        Route::post('move/{document}', 'moveByDocument')->name('leftovers.move-by-document');
                    });
                });


                Route::resource('/containers', ContainerController::class)->except(['destroy']);
                Route::prefix('containers')->group(function () {
                    Route::controller(ContainerController::class)->group(function () {
                        Route::get('table/filter', 'filter')->name('containers.filter');
                        Route::get('get-by-type/{id}', 'getContainersByType')
                            ->name('containers.get-by-type');
                        Route::get('all-data/{container}', 'getAllData')
                            ->name('containers.get-all-data');
                    });
                });


                Route::prefix('regulations')->group(function () {
                    Route::controller(RegulationController::class)->group(function () {
                        Route::get('/search', 'search')->name('regulations.search');
                        Route::get('/list', 'getList')->name('regulations.list');
                        Route::get('/{regulation}', 'show')->name('regulations.show')->withTrashed();
                        Route::post('/duplicate/{regulation}', 'duplicate')->name('regulations.duplicate')->withTrashed();
                        Route::delete('/archive/{regulation}', 'archive')->name('regulations.archive')->withTrashed();
                        Route::delete('/{regulation}', 'destroy')->name('regulations.destroy')->withTrashed();
                    });
                });
                Route::resource('/regulations', RegulationController::class)->except(['edit', 'create', 'destroy', 'show']);

                Route::prefix('residue-control')->group(function () {
                    Route::controller(ResidueController::class)->group(function () {
                        Route::get('/', 'index')->name('residue-control.index');
                        Route::get('/create', 'create')->name('residue-control.create');
                        Route::get('/catalog', 'catalog')->name('residue-control.catalog');
                    });
                });

                Route::prefix('contracts')->group(function () {
                    Route::controller(ContractController::class)->group(function () {
                        Route::post('/comment', 'createComment')->name('contracts.create-comment');
                        Route::post('/change-status', 'changeStatus')->name('contracts.change-status');
                        Route::get('table/filter', 'filter')->name('contracts.filter');
                    });
                });
                Route::resource('/contracts', ContractController::class);

                Route::prefix('invoices')->group(function () {
                    Route::controller(InvoiceController::class)->group(function () {
                        Route::get('table/filter', 'filter')->name('invoices.filter');
                        Route::get('table/obligations-filter', 'obligations_filter')->name('invoices.obligations-filter');

                        Route::get('/', 'index')->name('invoices.index');
                        Route::get('/create', 'create')->name('invoices.create');
                        Route::get('/view', 'show')->name('invoices.view');
                    });
                });

                Route::resource('type-goods', GoodsTypeController::class);
                Route::resource('type-categories', CategoryController::class);
                Route::get('type-categories/{category}/delete', [CategoryController::class, 'destroy'])
                    ->name('type-categories.delete');

                Route::resource('locations', LocationController::class);
                Route::get('locations/table/filter', [LocationController::class, 'filter'])->name('location.filter');


                Route::resource('/container-register', ContainerRegisterController::class)->only(['store', 'index', 'show', 'destroy']);
                Route::get('/container-register/table/filter', [ContainerRegisterController::class, 'filter'])->name('container-register.filter');
                Route::patch('/container_register/{container_register}/assign-cell', [ContainerRegisterController::class, 'assignCell']);

                Route::resource('/leftover-to-container', LeftoverToContainerController::class)->only(['store', 'destroy']);
                Route::get('/leftover-to-container/table/filter/{container_register_id}', [LeftoverToContainerController::class, 'filter'])->name('goods-to-container.filter');

                Route::controller(InventoryController::class)
                    ->name('inventory.')
                    ->prefix('inventory')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');

                        Route::get('/manual', 'manual')->name('manual');
                        Route::get('/manual/items', 'getManualItems')->name('inventory.manual.items');
                        Route::get('/manual/{cell}/leftovers', 'getManualLeftoversByCell')
                            ->whereUuid('cell')
                            ->name('manual.cell.leftovers');

                        Route::get('/manual/group/{group}/leftovers', 'getManualLeftoversByGroup')
                            ->whereUuid('group')
                            ->name('manual.group.leftovers');

                        Route::get('/table/filter', 'table')->name('table');
                        Route::get('/{inventory}/items', 'items')->name('items');
                        Route::get('/{inventory}/leftovers', 'leftovers')
                            ->whereUuid('inventory')
                            ->name('leftovers');
                        Route::get('/{inventory}/leftover/items', 'leftoverItems')
                            ->whereUuid('inventory')
                            ->name('inventory.leftovers.items');
                        Route::patch('leftovers/{leftovers}', 'leftoversUpdate')
                            ->whereUuid('leftovers')
                            ->name('leftovers.update');
                        Route::post('/{inventory}/leftovers', 'leftoversStore')
                            ->name('leftovers.store');
                        Route::get('/leftovers/{leftovers}', 'leftoverShow')
                            ->whereUuid('leftovers')
                            ->name('leftovers.show');

                        Route::get('leftovers/{leftovers}/package-info', 'getPackages')
                            ->whereUuid('leftovers')
                            ->name('inventory.leftovers.packages');

                        Route::delete('/leftovers/{leftovers}', 'leftoverDestroy')
                            ->whereUuid('leftovers')
                            ->name('leftovers.destroy');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/', 'store')->name('store');
                        Route::get('/{inventory}', 'show')->whereUuid('inventory')->name('show');
                        Route::post('/{item}/submit', 'itemStatus')
                            ->whereUuid('item')
                            ->name('item.submit');
                        Route::get('/{inventory}/proceed', 'transition')
                            ->whereUuid('inventory')->name('proceed')->defaults('action', 'proceed');
                        Route::get('/{inventory}/pause', 'transition')
                            ->whereUuid('inventory')->name('pause')->defaults('action', 'pause');
                        Route::get('/{inventory}/finish', 'transition')
                            ->whereUuid('inventory')->name('finish')->defaults('action', 'finish');
                        Route::get('/{inventory}/finish_before', 'transition')
                            ->whereUuid('inventory')->name('finish_before')->defaults('action', 'finish_before');
                        Route::get('/{inventory}/edit', 'edit')->whereUuid('inventory')->name('edit');
                        Route::put('/{inventory}', 'update')->whereUuid('inventory')->name('update');
                        Route::patch('/{inventory}', 'update')->whereUuid('inventory');
                        Route::match(['get', 'delete'], '/{inventory}/delete', 'destroy')
                            ->whereUuid('inventory')
                            ->name('destroy');
                        Route::post('/items/{item}/correction-quantity', 'correctItemQuantity')
                            ->name('items.correction-quantity');
                    });

                Route::resource('tasks', TaskController::class);

                Route::controller(TaskController::class)->prefix('tasks')->name('tasks.')->group(function () {
                    Route::post('/{task}/executors/{user}', 'assignExecutor')->name('executors.store');

                    Route::delete('/{task}/executors/{user}', 'removeExecutor')->name('executors.destroy');

                    Route::patch('/{task}/priority/{priority}', 'setPriority')->name('executors.destroy');

                    Route::get('/item/{task}/{product_id}/table/filter', 'taskItemFilter')->name('item.table.filter');

                    Route::post('/{task}/in-progress', 'moveInProgress')->name('in-progress');

                    Route::post('/{task}/cancel', 'cancel')->name('cancel');
                });


                Route::group(['prefix' => 'tasks'], function () {
                    Route::controller(TaskController::class)->group(function () {
                        Route::get('table/filter', [TaskController::class, 'filter'])->name('location.filter');
                        Route::group(['prefix' => 'tasks'], function () {
                            Route::post('item', 'addItem')->name('tasks.add-item');
                            Route::put('item/{task}', 'updateItem')->name('tasks.update-item');
                            Route::delete('item/{task}', 'deleteItem')->name('tasks.delete-item');
                        });
                    });
                });

                Route::group(['prefix' => 'stickers'], function () {
                    Route::get('/{type}', [StickerController::class, 'show'])->name('stickers.show');
                    Route::post('/print-labels', [PrintLabelController::class, 'generateCellLabels'])->name('stickers.print-labels');
                    Route::get('/print-labels/status/{jobId}', [PrintLabelController::class, 'checkStatus'])->name('stickers.print-labels.status');
                });

                Route::resource('leftovers-erp', LeftoverErpController::class)->only(['store', 'index', 'show', 'destroy']);
                Route::get('/leftovers-erp/table/filter', [LeftoverErpController::class, 'filter'])->name('leftovers-erp.filter');

                Route::get('package-info/{package_id}', [TerminalPackageController::class, 'getPackageInfo'])->name('package.info');

            });
        });
    });
});
