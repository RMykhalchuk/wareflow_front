<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Terminal\LeftoverController;
use App\Http\Controllers\Terminal\ManualMovementController;
use App\Http\Controllers\Terminal\PackageController;
use App\Http\Controllers\Terminal\ManualIncomeController;
use App\Http\Controllers\Terminal\Task\IncomeController;
use App\Http\Controllers\Terminal\Task\PickingController;
use App\Http\Controllers\Web\Container\ContainerController;
use App\Http\Controllers\Web\Container\ContainerRegisterController;
use App\Http\Controllers\Web\Document\DocumentController;
use App\Http\Controllers\Web\Document\Income\IncomeDocumentController;
use App\Http\Controllers\Web\Document\Income\IncomeLeftoverController;
use App\Http\Controllers\Web\Document\Outcome\OutcomeLeftoverController;
use App\Http\Controllers\Web\Goods\GoodsController;
use App\Http\Controllers\Web\Leftover\LeftoverController as WebLeftoverController;
use App\Http\Controllers\Web\Leftover\LeftoverToContainerController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\Warehouse\CellController;
use App\Http\Controllers\Web\Warehouse\RowController;
use App\Http\Controllers\Web\Warehouse\SectorController;
use App\Http\Controllers\Web\Warehouse\ZoneController;

Route::post('/login', [AuthController::class, 'login']);

Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

Route::post('/auth/user/refresh', [AuthController::class, 'refreshUser']);

Route::post('/auth/login/check', [AuthController::class, 'checkUser']);

Route::post('/auth/login/pin', [AuthController::class, 'checkPin']);

Route::put('/auth/login/pin', [AuthController::class, 'updatePin']);


Route::middleware(['auth:api', 'cors.disabled', 'company.context'])->group(function () {
    Route::get('/users', [AuthController::class, 'me']);

    Route::put('users/{user}/current-warehouse', [UserController::class, 'updateCurrentWarehouse']);

    Route::prefix('leftovers')->controller(LeftoverController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('cells', 'search');
        Route::get('{goods}/cells/{cell}', 'contents');
        Route::get('containers', 'searchContainer');
        Route::get('{goods}', 'show');         // /leftovers/{goodsId}
        Route::get('cells/{cell}', 'byCell');
    });

    Route::prefix('movement/manual')->controller(ManualMovementController::class)->group(function () {
        Route::get('cell/scan/{id}', 'scanCell');
        Route::get('container/scan/{id}', 'scanContainer');
        Route::get('/cells-containers/search', 'getCellAndContainer');

        Route::post('move', 'move');
    });

    Route::post('leftovers', [\App\Http\Controllers\Web\Leftover\LeftoverController::class, 'store']);

    Route::get('cells/{cell}', [LeftoverController::class, 'leftovers']);
    Route::get('containers/{containerId}', [LeftoverController::class, 'containerLeftovers']);


    Route::prefix('inventory')->controller(InventoryController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('{inventory}/items', 'items');
        Route::get('{inventory}/zone-items', 'itemsByZone');
        Route::get('{inventory}/leftovers', 'leftoversByInventory');
        Route::get('items/{inventory_item}/leftovers', 'leftoversByItem');
        Route::patch('leftovers/{leftovers}/quantity', 'correctLeftoverQuantity');
        Route::post('leftovers-sync', 'syncLeftovers');

        Route::get('{inventory}', 'show');
        Route::get('items/{inventory_item}', 'showItem');
        Route::get('{inventory}/items/{inventory_item}', 'showItemFromInventory');

        Route::post('{inventory}/leftovers', 'leftoversStoreApi')
            ->whereUuid('inventory');
        Route::post('items/{inventory_item}/leftovers-sync', 'syncLeftoversByItem')
            ->whereUuid('inventory_item');

        Route::get('items/{inventory_item}/containers-with-leftovers', 'containersWithLeftoversByItem')
            ->whereUuid('inventory_item')
            ->name('inventory.items.containers-with-leftovers');

        Route::patch('items/{inventory_item}/reset', 'reset')
            ->whereUuid('inventory_item')
            ->name('inventory.items.reset');

        Route::get('items/{inventory_item}/containers/{container}/leftovers', 'leftoversByItemAndContainer')
            ->whereUuid(['inventory_item', 'container'])
            ->name('inventory.items.leftovers.by-container');
    });


    Route::apiResource('/warehouses.zones', ZoneController::class)->except(['create', 'edit', 'show', 'index']);
    Route::apiResource('/zones.sectors', SectorController::class)->except(['create', 'edit', 'show', 'index']);
    Route::apiResource('/sectors.rows', RowController::class)->except(['create', 'edit', 'show', 'index']);
    Route::apiResource('/rows.cells', CellController::class)->except(['create', 'edit', 'show', 'index']);

    Route::get('/zones/{zone}/can-delete', [ZoneController::class, 'canDelete']);
    Route::get('/sectors/{sector}/can-delete', [SectorController::class, 'canDelete']);
    Route::get('/rows/{row}/can-delete', [RowController::class, 'canDelete']);
    Route::get('/cells/{cell}/can-delete', [CellController::class, 'canDelete']);

    Route::prefix('containers')->group(function () {
        Route::controller(ContainerController::class)->group(function () {
            Route::get('with-leftovers/{cell}', 'withLeftoversByCell')
                ->whereUuid('cell')
                ->name('containers.with-leftovers.by-cell');

            Route::get('table/filter', 'filter')->name('containers.filter');
            Route::get('get-by-type/{id}', 'getContainersByType')
                ->name('containers.get-by-type');
            Route::get('all-data/{container}', 'getAllData')
                ->name('containers.get-all-data');
        });
    });


    Route::get('sku/list', [GoodsController::class, 'skuList'])->name('sku.filter.list');

    Route::apiResource('container-register', ContainerRegisterController::class)->only(['store']);

    Route::patch('/container_register/{container_register}/assign-cell', [ContainerRegisterController::class, 'assignCell']);

    Route::apiResource('leftover-to-container', LeftoverToContainerController::class)->only(['store', 'destroy']);


    Route::get('package/leftovers/{leftover}', [WebLeftoverController::class, 'getPackageInfo'])->name('leftovers.package');

    Route::get('/document/{document_id}/task/table/filter', [DocumentController::class, 'taskFilter'])->name('document.task.filter');

    Route::post('outcome/{document}/free-selection', [DocumentController::class, 'updateFreeSelection'])->name('document.outcome.free-selection');

    Route::prefix('document')->group(function () {
        Route::prefix('income/leftover')->controller(IncomeLeftoverController::class)->group(function () {
            Route::post('/{document}/{goods_id}', 'store')->name('document.income.leftover.store');
            Route::put('/{incomeDocumentLeftover}', 'update')->name('document.income.leftover.update');
            Route::delete('/{incomeDocumentLeftover}', 'destroy')->name('document.income.leftover.delete');
            Route::get('/{document}/{goods_id}/table/filter', 'filter')->name('document.income.leftover.table.filter');
            Route::get('/{document}/progress', 'progress')->name('document.income.leftover.progress');
        });

        Route::post('income/{document}/task', [IncomeDocumentController::class, 'storeTask'])->name('document.income.task');
        Route::post('income/{document}/process', [IncomeDocumentController::class, 'process'])->name('document.income.process');

        Route::prefix('outcome/leftover')->controller(OutcomeLeftoverController::class)->group(function () {
            Route::post('/{document}', 'store')->name('document.outcome.leftover.store');
            Route::put('/{outcomeDocumentLeftover}', 'update')->name('document.outcome.leftover.update');
            Route::delete('/{outcomeDocumentLeftover}', 'destroy')->name('document.outcome.leftover.delete');
            Route::get('/{document}/{goods_id}/table/filter', 'filter')->name('document.outcome.leftover.table.filter');
            Route::get('/{document}/progress', 'progress')->name('document.outcome.leftover.progress');
        });
    });

    Route::get('package-info/{package_id}', [PackageController::class, 'getPackageInfo'])->name('package.info');


    Route::prefix('task/income')->group(function () {

        Route::get('/', [IncomeController::class, 'index'])->name('task.income.index');

        Route::get('/{task}', [IncomeController::class, 'view'])->name('task.income.view');

        Route::get('/{task}/goods/{goodsId}', [IncomeController::class, 'viewProduct'])->name('task.income.view-product');

        Route::post('/{task}/goods/{goodsId}/close', [IncomeController::class, 'closePosition'])->name('task.income.close-position');

        Route::post('/{task}/process', [IncomeController::class, 'process'])->name('task.income.process');

        Route::get('/goods/{goods}', [IncomeController::class, 'productInfo'])->name('task.income.product-info');
    });

    Route::prefix('task/income/manual')->controller(ManualIncomeController::class)->group(function () {

        Route::post('/position/close', 'closePosition');

        Route::delete('/position/{incomeDocumentLeftover}', 'revertPosition');

        Route::post('/{document}/process', 'closeIncome');

        Route::delete('/{document}', 'revertIncome');

        Route::get('/{document}/products', 'productList');

        Route::get('/{document}/products/{goods}', 'productView');
    });

    Route::prefix('task/picking')->controller(PickingController::class)->group(function () {

        Route::get('/','index')->name('task.picking.index');

        Route::get('/{task}', 'view');

        Route::get('/{document}/products/{cell}', 'getProductsByLocation');

        Route::post('/locations', 'getLocations');

        Route::get('{document}/{cell}/{goods}/view', 'viewProduct');

        Route::post('{document}/container/pick/{containerRegister}', 'pickUpContainer');

        Route::post('/{document}/take', 'takeLeftover');

        Route::post('/move-to-control/{document}', 'moveToControl');

        Route::post('/finish/{document}', 'finish');

        Route::delete('/{document}/{leftover}', 'deleteLeftover');
    });


});
