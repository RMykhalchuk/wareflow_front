<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\GoodsController;
use App\Http\Controllers\Api\KitsController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\DictionaryController;
use App\Http\Controllers\Web\Warehouse\WarehouseController;
use App\Http\Controllers\Web\Warehouse\WarehouseErpController;
use App\Http\Controllers\Api\LeftoverErpController as ApiLeftoverErpController;


Route::middleware(['auth:api', 'cors.disabled', 'company.context'])->group(function () {
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index']);
        Route::get('/{id}', [CompanyController::class, 'show']);
        Route::post('/physical', [CompanyController::class, 'storePhysical']);
        Route::post('/legal', [CompanyController::class, 'storeLegal']);
        Route::put('/update-legal/{company}', [CompanyController::class, 'updateLegalCompany']);
        Route::put('/update-physical/{company}', [CompanyController::class, 'updatePhysicalCompany']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/goods-categories', [CategoryController::class, 'getGoodsCategories']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{company}', [CategoryController::class, 'update']);
    });

    Route::apiResource('warehouses', WarehouseController::class)->except(['create', 'edit', 'show', 'index']);
    Route::apiResource('warehouse-erp', WarehouseErpController::class)->except(['create', 'show', 'index'])->parameters([
                                                                                                                            'warehouse-erp' => 'warehouse_erp',
                                                                                                                        ]);
    Route::get('warehouse-erp/{id}', [WarehouseErpController::class, 'getWarehouseById']);
    Route::get('warehouse-erp', [WarehouseErpController::class, 'getWarehouses']);

    Route::apiResource('leftovers-erp', ApiLeftoverErpController::class)->except(['create', 'show', 'index'])->parameters([
                                                                                                                              'leftovers-erp' => 'leftover_erp',
                                                                                                                          ]);
    Route::post('leftovers-erp/bulk-upsert', [ApiLeftoverErpController::class, 'bulkUpsert']);
    Route::get('leftovers-erp/{id}', [ApiLeftoverErpController::class, 'getLeftoverById']);
    Route::get('leftovers-erp', [ApiLeftoverErpController::class, 'getLeftovers']);

    Route::get('warehouses/list', [WarehouseController::class, 'getWarehouses']);
    Route::get('warehouses/{id}', [WarehouseController::class, 'getWarehouseById']);
    Route::get('warehouses/meta/warehouse-type', [WarehouseController::class, 'getWarehouseTypes']);


    Route::apiResource('goods/kits', KitsController::class)
        ->parameters(['kits' => 'goods']);

    Route::apiResource('goods', GoodsController::class)->parameter('goods', 'goods');;

    Route::apiResource('goods/{goods}/packages', PackageController::class);

    Route::prefix('dictionary')->controller(DictionaryController::class)
        ->middleware('company.context')->group(function () {
            //make exception for company
            Route::get('/company', 'getCompanyList');
            //    Route::get('/enums/{dictionary}', 'getEnumsList');
            Route::get('/adr', 'getAdr');

            Route::get('/countries', 'getCountry');

            Route::get('/measurement_units', 'getProductUnits');

            Route::get('/package_types', 'getPackageType');
        });
});
