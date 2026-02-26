<?php

namespace App\Http\Controllers\Web\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Transport\TruckRequest;
use App\Http\Requests\Web\Transport\TruckWithoutTrailer;
use App\Http\Resources\Web\TransportModelResource;
use App\Interfaces\StoreImageInterface;

use App\Models\Dictionaries\TransportBrand;
use App\Models\Entities\Transport\Transport;
use App\Services\Web\Transport\TransportServiceInterface;
use App\Tables\Transport\TableFacade;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

final class TransportController extends Controller
{
    protected $transportService;

    public function __construct(TransportServiceInterface $transportService)
    {
        $this->transportService = $transportService;
    }

    public function index(): View
    {
        return view('transport.index', $this->transportService->getAllTransports());
    }

    public function create(): View
    {
        return view('transport.create', $this->transportService->prepareCreateData());
    }

    public function store(TruckWithoutTrailer $request): Response
    {
        Transport::storeWithoutTrailer($request);
        return response('OK');
    }

    public function storeWithAdditional(TruckRequest $request): Response
    {
        Transport::storeWithTrailer($request);
        return response('OK');
    }

    public function show(Transport $transport): View
    {
        return view('transport.profile', $this->transportService->prepareShowData($transport));
    }

    public function edit(Transport $transport): View
    {
        return view('transport.edit', $this->transportService->prepareEditData($transport));
    }

    public function deleteImage(Transport $transport, StoreImageInterface $image): RedirectResponse
    {
        $image->deleteImage($transport, 'transport');
        return redirect()->back();
    }

    public function update(TruckWithoutTrailer $request, Transport $transport): Response
    {
        $transport->updateWithoutTrailer($request);
        return response('OK');
    }

    public function updateWithAdditional(TruckRequest $request, Transport $transport): Response
    {
        $transport->updateWithTrailer($request);
        return response('OK');
    }

    public function destroy(Transport $transport, StoreImageInterface $image): RedirectResponse
    {
        $transport->delete();
        $image->deleteImage($transport, 'transport');
        return redirect()->route('transports.index');
    }

    public function getModelByBrand(TransportBrand $transportBrand): AnonymousResourceCollection
    {
        return TransportModelResource::collection($transportBrand->models);
    }

    public function filter()
    {
        return TableFacade::getFilteredData();
    }
}
