<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

final class LayoutController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {

        // return view('additional-equipment.index');
        //return view('additional-equipment.create');
        // return view('additional-equipment.show');
        //return view('additional-equipment.profile');

        //  return view('company.search-company');
        // return view('company.company-view');
        // return view('company.edit-legal');
        //return view('company.edit-physical');

        // return view('transport.index');
        //return view('transport.create');
        // return view('transport.show');
        //return view('transport.profile');

        // return view('warehouse.index');
        // return view('warehouse.create');
        // return view('warehouse.revision');
        // return view('warehouse.warehouse-data-edit');
        // return view('warehouse.warehouse-schedule-edit');

        //return view('warehouse/sector.index');
        //return view('warehouse/sector.create');

        //return view('warehouse/row.index');

        //return view('sku.index');
        //return view('sku.create');
        // return view('sku.edit');
        // return view('sku.full-info');

        //return view('documents.index');
        // return view('documents.create');
        //return view('documents.template-1');
        //return view('documents.info-2');
        //return view('documents.info-3');
        //return view('documents.info-4');

        //return view('documents.list');

        //return view('document-types.index');
        // return view('document-types.create');
        //return view('document-types.preview');

        // return view('onboarding.create-company');
        // return view('workspaces.create');

        // return view('workspaces.workspaces-list');
        // return view('workspaces.workspace-settings');

        // return view('transport-planning.days-list');
        // return view('transport-planning.create-of-TP');
        // return view('transport-planning.planning-list');
        // return view('transport-planning.tn-details');

        // return view('leftovers.index');
        //return view('leftovers.index-1');
        //return view('leftovers.index-2');
        //return view('leftovers.erp');

        // return view('user.user-page');

        // return view('service.view');
        // return view('service.create');
        // return view('service.index');

        // return view('container.index');
        // return view('container.create');
        //  return view('container.full-info');

        // return view('contract.index');
        // return view('contract.create');
        //  return view('contract.view');
        //  return view('container.full-info');

        // return view('regulation.index');

        // return view('invoice.view');
        // return view('invoice.invoice-table');
        // return view('invoice.invoicing');

        // return view('residue-control.index');
        // return view('residue-control.create');
        // return view('match.log-pending-review');
        // return view('match.log-rejected');
        // return view('match.log-in-progress');
        // return view('match.log-confirmed');
        // return view('match.setting-logistic');
        // return view('old-match.setting-dispatcher');
        // return view('match.disp-top-up');
        // return view('match.disp-joint-ftl');
        // return view('match.disp-lg-transport');
        // return view('match.disp-backhaul');
        // return view('match.disp-created');
        // return view('match.disp-draft');

        return view('match.logist.pages.progress');
        // return view('match.logist.pages.pending-review');
        // return view('match.logist.pages.confirmed');
        // return view('match.logist.pages.rejected');
    }
}
