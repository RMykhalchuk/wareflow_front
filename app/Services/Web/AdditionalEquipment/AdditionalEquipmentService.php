<?php

namespace App\Services\Web\AdditionalEquipment;

use App\Interfaces\StoreImageInterface;
use App\Models\{Entities\Address\Country,
    Entities\Company\Company,
    Entities\System\Workspace,
    Entities\Transport\AdditionalEquipment,
    Entities\Transport\Transport};
use Illuminate\Http\Request;

class AdditionalEquipmentService implements AdditionalEquipmentServiceInterface
{
    public function getIndexData(): \Illuminate\Support\Collection
    {
        return AdditionalEquipment::all(
            [
                'id', 'brand_id', 'model_id', 'type_id', 'license_plate',
                'download_methods', 'company_id', 'transport_id'
            ]);
    }

    public function getCreateFormData(): array
    {
        return [
            'countries' => Country::all(),
            'transports' => Transport::with('equipment')->get(['id', 'brand_id', 'model_id']),
        ];
    }

    public function getEditFormData(AdditionalEquipment $equipment): array
    {
        $companies = Company::whereHas('companiesInWorkspace', function ($query) {
            $query->where('workspace_id', Workspace::current());
        })->orWhere('workspace_id', Workspace::current())->get();

        return array_merge($this->getCreateFormData(), [
            'companies' => $companies,
            'transportEquipment' => $equipment
        ]);
    }

    public function storeEquipment(Request $request): void
    {
        AdditionalEquipment::store($request);
    }

    public function updateEquipment(Request $request, AdditionalEquipment $equipment): void
    {
        $equipment->edit($request);
    }

    public function deleteWithImage(AdditionalEquipment $equipment, StoreImageInterface $image): void
    {
        $equipment->delete();
        $image->deleteImage($equipment, 'transport-equipment');
    }
}
