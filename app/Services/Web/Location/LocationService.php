<?php

namespace App\Services\Web\Location;

use App\Models\Entities\Location;
use App\Models\Entities\WarehouseComponents\Warehouse;
use Exception;

class LocationService implements LocationServiceInterface
{

    public function all()
    {
        return Location::all();
    }

    public function create(array $data): Location
    {
        return Location::create($data);
    }

    public function update(Location $location, array $data): Location
    {
        $location->update($data);
        return $location;
    }

    public function delete(Location $location): bool
    {
        if (Warehouse::where('location_id', $location->id)->exists()) {
            throw new Exception("Location [{$location->id}] is already in use.");
        }

        return $location->delete();
    }
}
