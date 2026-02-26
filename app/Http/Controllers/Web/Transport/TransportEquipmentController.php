<?php

namespace App\Http\Controllers\Web\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Transport\EquipmentRequest;
use App\Http\Resources\Web\AdditionalEquipmentModelResource;
use App\Interfaces\StoreImageInterface;
use App\Models\Dictionaries\AdditionalEquipmentBrand;
use App\Models\Entities\Transport\AdditionalEquipment;
use App\Services\Web\AdditionalEquipment\AdditionalEquipmentServiceInterface;
use App\Tables\AdditionalEquipment\TableFacade;

final class TransportEquipmentController extends Controller
{
    public function index(AdditionalEquipmentServiceInterface $service)
    {
        $additionalEquipments = $service->getIndexData();
        return view('additional-equipment.index', compact('additionalEquipments'));
    }

    public function create(AdditionalEquipmentServiceInterface $service)
    {
        return view('additional-equipment.create', $service->getCreateFormData());
    }

    public function edit(AdditionalEquipment $transportEquipment, AdditionalEquipmentServiceInterface $service)
    {
        return view('additional-equipment.edit', $service->getEditFormData($transportEquipment));
    }

    public function store(EquipmentRequest $request, AdditionalEquipmentServiceInterface $service)
    {
        $service->storeEquipment($request);
        return response('OK');
    }

    public function update(EquipmentRequest $request, AdditionalEquipment $transportEquipment, AdditionalEquipmentServiceInterface $service)
    {
        $service->updateEquipment($request, $transportEquipment);
        return response('OK');
    }

    public function destroy(AdditionalEquipment $transportEquipment, StoreImageInterface $image, AdditionalEquipmentServiceInterface $service)
    {
        $service->deleteWithImage($transportEquipment, $image);
        return redirect()->route('transport-equipments.index');
    }

    public function deleteImage(AdditionalEquipment $transportEquipment, StoreImageInterface $image)
    {
        $image->deleteImage($transportEquipment, 'transport-equipment');
        return redirect()->back();
    }

    public function show(AdditionalEquipment $transportEquipment)
    {
        return view('additional-equipment.profile', compact('transportEquipment'));
    }

    public function getModelByBrand(AdditionalEquipmentBrand $additionalEquipmentBrand)
    {
        return AdditionalEquipmentModelResource::collection($additionalEquipmentBrand->models);
    }

    public function filter()
    {
        return TableFacade::getFilteredData();
    }
}
