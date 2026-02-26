<?php

namespace App\Services\Web\TransportPlanning;

interface TransportPlanningServiceInterface
{
    public function getFormData(): array;
    public function storePlanning(\Illuminate\Http\Request $request): int;
    public function getPlanningDetails(int $id): array;
    public function deletePlanning(\App\Models\Entities\TransportPlanning\TransportPlanning $transportPlanning): void;
    public function getPlanningByDate(string $date): array;
    public function updateStatus(array $data, \App\Models\Dictionaries\TransportPlanningToStatus $status): int;
    public function addStatus(array $data): int;
    public function deleteStatus(\App\Models\Dictionaries\TransportPlanningToStatus $status): void;
    public function addFailure(array $data, \App\Models\Dictionaries\TransportPlanningToStatus $status): int;
    public function getDocuments(): array;
}
