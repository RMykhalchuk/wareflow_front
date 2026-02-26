<?php

namespace App\Services\Web\Registers;

use App\Models\Dictionaries\Register;
use Illuminate\Http\Request;

interface RegisterServiceInterface
{
    public function prepareGuardianData(): array;

    public function prepareStorekeeperData(): array;

    public function storeRegister(Request $request): Register;
}
