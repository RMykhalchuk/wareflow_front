<?php


use App\Http\Controllers\Web\Document\ContainerInDocumentController;
use App\Http\Controllers\Web\Document\DoctypeFieldController;
use App\Http\Controllers\Web\Document\DocumentController;
use App\Http\Controllers\Web\Document\DocumentRelationController;
use App\Http\Controllers\Web\Document\DocumentTypeController;
use App\Http\Controllers\Web\Document\Income\IncomeDocumentController;
use App\Http\Controllers\Web\Document\Income\IncomeLeftoverController;
use App\Http\Controllers\Web\Document\Outcome\OutcomeDocumentController;
use App\Http\Controllers\Web\Document\Outcome\OutcomeLeftoverController;
use App\Http\Controllers\Web\Document\SkuInDocumentController;

use Illuminate\Support\Facades\Route;

Route::resource('document-type', DocumentTypeController::class)->except(['show']);
Route::prefix('document-type')->group(function () {
    Route::controller(DocumentTypeController::class)->group(function () {
        Route::get('/preview', 'preview')->name('document-type.preview');
        Route::post('/draft', 'storeDraft')->name('document-type.draft.create');
        Route::post('/field', [DoctypeFieldController::class, 'store'])->name('field.create');
        Route::delete('/field/{key}', [DoctypeFieldController::class, 'destroy'])->name('field.destroy');
        Route::get('table/filter', 'filter');

        //should be in end of group
        Route::post('/{status}/{document_type}', 'changeStatus')->name('document-type.status.change');
    });
});

Route::resource('document', DocumentController::class)->except(['create']);
Route::prefix('document')->group(function () {
    Route::controller(DocumentController::class)->group(function () {
        Route::get('table/filter', 'filter')->name('document.filter');
        Route::get('{document}/containers/table/filter', 'containerFilter')->name('document.containers.filter');
        Route::get('{document}/outcome/containers/table/filter', 'outcomeContainerFilter')->name('document.outcome.containers.filter');
        Route::get('table/{document_type}', 'table')->name('document.table');
        Route::post('table/create', 'createRelatedDocument')->name('related-document.create');
        Route::get('create/{document_type}', 'create')->name('document.create');
        Route::post('outcome/{document}/free-selection', 'updateFreeSelection')->name('document.outcome.free-selection');
        Route::post('state/{document}', 'setState')->name('document.set-state');
        Route::get('{document_id}/task/table/filter', 'taskFilter')->name('document.task.filter');
    });

    Route::post('income/{document}/task', [IncomeDocumentController::class, 'storeTask'])->name('document.income.task');
    Route::post('income/{document}/process', [IncomeDocumentController::class, 'process'])->name('document.income.process');

    Route::post('outcome/{document}/task', [OutcomeDocumentController::class, 'storeTask'])->name('document.outcome.task');
    Route::post('outcome/{document}/process', [OutcomeDocumentController::class, 'process'])->name('document.outcome.process');


    Route::prefix('income/leftover')->controller(IncomeLeftoverController::class)->group(function () {
        Route::post('/{document}/{goods_id}', 'store')->name('document.income.leftover.store');
        Route::put('/{incomeDocumentLeftover}', 'update')->name('document.income.leftover.update');
        Route::delete('/{incomeDocumentLeftover}', 'destroy')->name('document.income.leftover.delete');
        Route::get('/{document}/{goods_id}/table/filter', 'filter')->name('document.income.leftover.table.filter');
        Route::get('/{document}/progress', 'progress')->name('document.income.leftover.progress');
    });

    Route::prefix('outcome/task')->controller(OutcomeDocumentController::class)->group(function () {
        Route::post('//{goods_id}', 'store')->name('document.income.leftover.store');

    });

    Route::prefix('outcome/leftover')->controller(OutcomeLeftoverController::class)->group(function () {
        Route::post('/{document}', 'store')->name('document.outcome.leftover.store');
        Route::put('/{outcomeDocumentLeftover}', 'update')->name('document.outcome.leftover.update');
        Route::delete('/{outcomeDocumentLeftover}', 'destroy')->name('document.outcome.leftover.delete');
        Route::get('/{document}/{goods_id}/table/filter', 'filter')->name('document.outcome.leftover.table.filter');
        Route::get('/{document}/progress', 'progress')->name('document.outcome.leftover.progress');

        Route::post('/{document}/reserve', 'reserve')->name('document.outcome.leftover.reserve');
        Route::delete('/{document}/reserve', 'removeReservation')->name('document.outcome.leftover.remove.reserve');
    });
});

Route::prefix('document/sku')->group(function () {
    Route::controller(SkuInDocumentController::class)->group(function () {
        Route::get('table/filter', 'filter')->name('sku-document.filter');
        Route::post('/', 'store')->name('sku-document.store');
        Route::post('/table', 'tableStore')->name('sku-document.table');
    });
});

Route::prefix('document/container')->group(function () {
    Route::controller(ContainerInDocumentController::class)->group(function () {
        Route::get('table/filter', 'filter')->name('container-document.filter');
        Route::post('/', 'store')->name('container-document.store');
        Route::post('/table', 'tableStore')->name('container-document.table');
    });
});


Route::prefix('document/related')->controller(DocumentRelationController::class)->group(function () {
    Route::post('/', 'store')->name('related-document.store');
    Route::delete('/{document_id}/{related_id}', 'delete')->name('related-document.delete');
    Route::get('/filter', 'filter')->name('related-document.filter');
});
