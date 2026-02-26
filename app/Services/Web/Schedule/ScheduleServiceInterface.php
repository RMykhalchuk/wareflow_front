<?php

namespace App\Services\Web\Schedule;

use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;

interface ScheduleServiceInterface
{
    public function prepareEditScheduleData(User $user): array;

    public function updateUserSchedule(Request $request, User $user): void;

    public function updateWarehouseSchedule(Request $request, Warehouse $warehouse): void;
}
