<?php

namespace App\Services\Web\TransportPlanning;

use App\Models\{Dictionaries\TransportPlanningFailureType,
    Dictionaries\TransportPlanningStatus,
    Dictionaries\TransportPlanningToStatus,
    Entities\Address\AddressDetails,
    Entities\Company\Company,
    Entities\Document\Document,
    Entities\System\Workspace,
    Entities\TransportPlanning\TransportPlanning,
    Entities\TransportPlanning\TransportPlanningFailure,
    Transport\AdditionalEquipment,
    Transport\Transport,
    User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportPlanningService implements TransportPlanningServiceInterface
{
    public function getFormData(): array
    {
        $companies = Company::where('workspace_id', Workspace::current())
            ->get([
                      'id',
                      DB::raw("CASE
                    WHEN companies.company_type_id = 1 THEN (SELECT CONCAT(physical_companies.first_name, ' ', physical_companies.surname) FROM physical_companies WHERE physical_companies.id = companies.company_id)
                    ELSE (SELECT name FROM legal_companies WHERE legal_companies.id = companies.company_id)
                END as name")
                  ]);

        return [
            'companies' => $companies,
            'transports' => Transport::with(['brand', 'model'])->get(),
            'additionalEquipments' => AdditionalEquipment::with(['brand', 'model'])->get(),
            'drivers' => User::all(['id', 'name', 'surname']),
        ];
    }

    public function storePlanning(Request $request): int
    {
        return TransportPlanning::store($request);
    }

    public function getPlanningDetails(int $id): array
    {
        return [
            'planning' => (new TransportPlanning())->getItem($id),
            'allStatuses' => TransportPlanningStatus::all(),
            'allAddresses' => AddressDetails::addFullAddress()->get(),
            'allFailureTypes' => TransportPlanningFailureType::all(),
        ];
    }

    public function deletePlanning(TransportPlanning $transportPlanning): void
    {
        $transportPlanning->delete();
    }

    public function getPlanningByDate(string $date): array
    {
        return [
            'transportPlannings' => (new TransportPlanning())->getByDate($date),
            'allStatuses' => TransportPlanningStatus::all(),
            'allAddresses' => AddressDetails::addFullAddress()->get(),
            'allFailureTypes' => TransportPlanningFailureType::all(),
        ];
    }

    public function updateStatus(array $data, TransportPlanningToStatus $status): int
    {
        $status->fill($data)->save();
        return $status->id;
    }

    public function addStatus(array $data): int
    {
        return TransportPlanningToStatus::create($data)->id;
    }

    public function deleteStatus(TransportPlanningToStatus $status): void
    {
        $status->delete();
    }

    public function addFailure(array $data, TransportPlanningToStatus $status): int
    {
        $failure = TransportPlanningFailure::updateOrCreate(['status_id' => $status->id], $data);
        return $failure->id;
    }

    public function getDocuments(): array
    {
        return Document::whereHas('documentType', function ($q) {
            $q->where('name', 'Товарна накладна');
        })->get()->toArray();
    }
}

