<?php

namespace App\Services\Web\Transport;



use App\Models\Entities\Transport\Transport;

interface TransportServiceInterface
{
    public function getAllTransports(): array;

    public function prepareCreateData(): array;

    public function prepareEditData(Transport $transport): array;

    public function prepareShowData(Transport $transport): array;
}
