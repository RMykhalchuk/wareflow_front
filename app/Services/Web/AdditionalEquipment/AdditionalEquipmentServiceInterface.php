<?php

namespace App\Services\Web\AdditionalEquipment;

use App\Models\Entities\Transport\AdditionalEquipment;

interface AdditionalEquipmentServiceInterface
{
    public function getIndexData(): \Illuminate\Support\Collection;
    public function getCreateFormData(): array;
    public function getEditFormData(AdditionalEquipment $equipment): array;
    public function storeEquipment(\Illuminate\Http\Request $request): void;
    public function updateEquipment(\Illuminate\Http\Request $request, AdditionalEquipment $equipment): void;
    public function deleteWithImage(AdditionalEquipment $equipment, \App\Interfaces\StoreImageInterface $image): void;
}
