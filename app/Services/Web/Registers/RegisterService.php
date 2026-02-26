<?php

namespace App\Services\Web\Registers;

use App\Events\RegistersChangedStatus;
use App\Models\Dictionaries\DownloadZone;
use App\Models\Dictionaries\Register;
use App\Models\Dictionaries\TransportDownload;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterService implements RegisterServiceInterface
{
    public function prepareGuardianData(): array
    {
        $storekeepers = User::all(['id', 'surname', 'name']);
        $warehouses = Warehouse::all(['id', 'name']);

        return compact('storekeepers', 'warehouses');
    }

    public function prepareStorekeeperData(): array
    {
        $storekeepers = User::all(['id', 'surname', 'name']);
        $managers = User::all(['id', 'surname', 'name']);
        $downloadZone = DownloadZone::all();
        $transportDownload = TransportDownload::all();
        $warehouses = Warehouse::all(['id', 'name']);

        return compact('storekeepers', 'managers', 'downloadZone', 'transportDownload', 'warehouses');
    }

    public function storeRegister(Request $request): Register
    {
        $register = null;
        for ($i = 0; $i < 100; $i++) {
            $register = Register::create(
                [
                    'auto_name' => $request->auto_name,
                    'time_arrival' => $request->arrive,
                    'status_id' => 1,
                ]);
        }
        broadcast(new RegistersChangedStatus($register->fresh()));

        return $register;
    }
}
