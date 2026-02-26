<?php


use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\WorkspacesController;
use Illuminate\Support\Facades\Route;


Route::prefix('companies')->controller(CompanyController::class)->group(function () {
    Route::post('create-legal', 'storeLegalCompany')->name('create.legal-company');
    Route::post('create-physical', 'storePhysicalCompany')->name('create.physical-company');
    Route::post('delete-image/{company}', 'removeImage')->name('company.delete-image');
    Route::post('add-to-workspace/{company}', 'addCompanyToWorkspace')->name('company.add-to-workspace');
    Route::get('find', 'find');
});

Route::prefix('workspaces')->group(function () {
    Route::post('companies/create-physical', [CompanyController::class, 'storePhysicalCompany'])
        ->name('workspace.create.physical-company');
    Route::post('companies/create-legal', [CompanyController::class, 'storeLegalCompany'])
        ->name('workspace.create.legal-company');
});

Route::prefix('workspaces')->controller(WorkspacesController::class)->group(function () {
    Route::post('/change-selected-workspace', 'changeSelectedWorkspace')->name('workspaces.change-selected-workspace');
    Route::post('add-user', 'addUserToWorkspace')->name('workspaces.add-user-to-workspace');
    Route::get('/create/{company_id?}', 'create')->name('workspaces.create');
    Route::get('/create-company', 'createCompany')->name('workspaces.create-company');
    Route::post('/approve', 'approveUserToWorkspace')->name('workspaces.approve');
    Route::post('/unapprove', 'unapproveUserToWorkspace')->name('workspaces.unapprove');
    Route::post('/request/send', 'sendJoinRequest');

    //should be at the end of post routes
    Route::post('/{company?}', 'store')->name('workspaces.store');
});
