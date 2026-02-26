<?php

namespace App\Services\Web\Location;

use App\Models\Entities\Location;

interface LocationServiceInterface
{
    public function all();

    public function create(array $data): Location;

    public function update(Location $location, array $data): Location;

    public function delete(Location $location): bool;
}
