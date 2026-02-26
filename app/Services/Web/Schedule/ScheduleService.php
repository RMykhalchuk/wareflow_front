<?php

namespace App\Services\Web\Schedule;

use App\Models\Dictionaries\ExceptionType;
use App\Models\Entities\Schedule\SchedulePattern;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleService implements ScheduleServiceInterface
{
    public function prepareEditScheduleData(User $user): array
    {
        $patterns = SchedulePattern::all();
        $exceptions = ExceptionType::all('id', 'name');
        return compact('user', 'patterns', 'exceptions');
    }

    public function updateUserSchedule(Request $request, User $user): void
    {
        $schedule = json_decode($request->graphic, true);
        $conditions = json_decode($request->conditions, true);

        $user->updateSchedule($schedule);
        $user->updateConditions($conditions);
    }

    public function updateWarehouseSchedule(Request $request, Warehouse $warehouse): void
    {
        $schedule = json_decode($request->graphic, true);
        $conditions = json_decode($request->conditions, true);

        $warehouse->updateSchedule($schedule);
        $warehouse->updateConditions($conditions);
    }
}
