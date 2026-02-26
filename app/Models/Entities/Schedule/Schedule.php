<?php

namespace App\Models\Entities\Schedule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Schedule extends Model
{

    protected $guarded = [];

    public static function store(array $schedule, $userId = null, $warehouseId = null): void
    {
        $scheduleArray = [];

        foreach ($schedule as $dayItem) {
            $dayName = array_key_first($dayItem);
            $dayData = $dayItem[$dayName];

            if ($dayData === 'holiday') {
                $scheduleArray[] = [
                    'weekday'        => $dayName,
                    'is_day_off'     => true,
                    'start_at'       => null,
                    'end_at'         => null,
                    'break_start_at' => null,
                    'break_end_at'   => null,
                    'user_id'        => $userId,
                    'warehouse_id'   => $warehouseId,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            } else {
                $scheduleArray[] = [
                    'weekday'        => $dayName,
                    'is_day_off'     => false,
                    'start_at'       => $dayData[0],
                    'end_at'         => $dayData[1],
                    'break_start_at' => $dayData[2],
                    'break_end_at'   => $dayData[3],
                    'user_id'        => $userId,
                    'warehouse_id'   => $warehouseId,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        Schedule::insert($scheduleArray);
    }

}
