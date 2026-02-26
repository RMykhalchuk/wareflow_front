<?php

namespace App\Http\Controllers\Web\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\TransportPlanning\AddFailureRequest;
use App\Http\Requests\Web\TransportPlanning\DestroyStatusRequest;
use App\Http\Requests\Web\TransportPlanning\DestroyTransportPlanningRequest;
use App\Http\Requests\Web\TransportPlanning\StoreStatusRequest;
use App\Http\Requests\Web\TransportPlanning\UpdateStatusRequest;
use App\Models\Dictionaries\TransportPlanningToStatus;
use App\Models\Entities\TransportPlanning\TransportPlanning;
use App\Services\Web\TransportPlanning\TransportPlanningServiceInterface;
use App\Tables\GoodsInvoices\TableFacade as GoodsTableFacade;
use App\Tables\GoodsInvoicesByPlanning\TableFacade as GoodsByPlanningTableFacade;
use App\Tables\TransportPlaning\TableFacade;
use App\Tables\TransportPlaning\TransportRequestByPlanning\TableFacade as TransportRequestByPlanningFacade;
use App\Tables\TransportRequest\TableFacade as TransportRequestFacade;
use Illuminate\Http\Request;


final class TransportPlanningController extends Controller
{
    public function index()
    {
        $transportPlanning = TransportPlanning::all();
        return view('transport-planning.days-list', compact('transportPlanning'));
    }

    public function create(TransportPlanningServiceInterface $service)
    {
        return view('transport-planning.create-of-TP', $service->getFormData());
    }

    public function store(Request $request, TransportPlanningServiceInterface $service)
    {
        $id = $service->storePlanning($request);
        return response()->json(['transport_planning_id' => $id]);
    }

    public function getDocuments(TransportPlanningServiceInterface $service)
    {
        return response()->json(['documents' => $service->getDocuments()]);
    }

    public function show(TransportPlanning $transportPlanning, TransportPlanningServiceInterface $service)
    {
        $data = $service->getPlanningDetails($transportPlanning->id);
        return view('transport-planning.tn-details', $data);
    }

    public function destroy(DestroyTransportPlanningRequest $request, TransportPlanning $transportPlanning, TransportPlanningServiceInterface $service)
    {
        $service->deletePlanning($transportPlanning);
        return response()->json('Successful destroy');
    }

    public function listByDate($date, TransportPlanningServiceInterface $service)
    {
        $data = $service->getPlanningByDate($date);
        return view('transport-planning.planning-list', array_merge($data, ['date' => $date]));
    }

    public function updateStatus(UpdateStatusRequest $request, TransportPlanningToStatus $status, TransportPlanningServiceInterface $service)
    {
        $statusId = $service->updateStatus($request->except('_token'), $status);
        return response()->json(['status_id' => $statusId]);
    }

    public function addStatus(StoreStatusRequest $request, TransportPlanningServiceInterface $service)
    {
        $statusId = $service->addStatus($request->except('_token'));
        return response()->json(['status_id' => $statusId]);
    }

    public function deleteStatus(DestroyStatusRequest $request, TransportPlanningToStatus $status, TransportPlanningServiceInterface $service)
    {
        $service->deleteStatus($status);
        return response()->json('Successful destroy');
    }

    public function addFailure(AddFailureRequest $request, TransportPlanningToStatus $status, TransportPlanningServiceInterface $service)
    {
        $failureId = $service->addFailure($request->except('_token'), $status);
        return response()->json(['failure_id' => $failureId]);
    }

    public function filter()
    {
        return TableFacade::getFilteredData();
    }

    public function transportRequestFilter()
    {
        return TransportRequestFacade::getFilteredData();
    }

    public function goodsInvoicesFilter()
    {
        return GoodsTableFacade::getFilteredData();
    }

    public function goodsInvoicesByPlanningFilter($id)
    {
        return GoodsByPlanningTableFacade::getFilteredData($id);
    }

    public function transportRequestByPlanningFilter($id)
    {
        return TransportRequestByPlanningFacade::getFilteredData($id);
    }
}
