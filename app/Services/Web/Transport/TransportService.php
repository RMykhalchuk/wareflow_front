<?php

namespace App\Services\Web\Transport;

use App\Models\Dictionaries\Adr;
use App\Models\Dictionaries\TransportBrand;
use App\Models\Dictionaries\TransportCategory;
use App\Models\Dictionaries\TransportDownload;
use App\Models\Dictionaries\TransportType;
use App\Models\Entities\Address\Country;
use App\Models\Entities\Company\Company;
use App\Models\Entities\System\Workspace;
use App\Models\Entities\Transport\AdditionalEquipment;
use App\Models\Entities\Transport\Transport;


class TransportService implements TransportServiceInterface
{
    public function getAllTransports(): array
    {
        $transports = Transport::all();
        return compact('transports');
    }

    public function prepareCreateData(): array
    {
        $brands = TransportBrand::all();
        $countries = Country::all();
        $types = TransportType::all();
        $categories = TransportCategory::all();
        $download_methods = TransportDownload::all();
        $companies = Company::whereHas('companiesInWorkspace', function ($query) {
            $query->where('workspace_id', Workspace::current());
        })->orWhere('workspace_id', Workspace::current())->get();
        $additionalEquipments = AdditionalEquipment::with(['brand','model'])->get(['id', 'brand_id', 'model_id']);
        $adrs = Adr::all();

        return compact(
            'companies',
            'brands',
            'countries',
            'types',
            'categories',
            'download_methods',
            'additionalEquipments',
            'adrs'
        );
    }

    public function prepareEditData(Transport $transport): array
    {
        $brands = TransportBrand::all();
        $countries = Country::all();
        $types = TransportType::all();
        $categories = TransportCategory::all();
        $download_methods = TransportDownload::all();
        $companies = Company::whereHas('companiesInWorkspace', function ($query) {
            $query->where('workspace_id', Workspace::current());
        })->orWhere('workspace_id', Workspace::current())->get();
        $additionalEquipments = AdditionalEquipment::all(['id', 'brand_id', 'model_id']);
        $adrs = Adr::all();

        return compact(
            'companies',
            'brands',
            'countries',
            'types',
            'categories',
            'download_methods',
            'transport',
            'additionalEquipments',
            'adrs'
        );
    }

    public function prepareShowData(Transport $transport): array
    {
        return compact('transport');
    }
}
