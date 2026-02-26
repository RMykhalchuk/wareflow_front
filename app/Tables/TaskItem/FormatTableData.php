<?php

namespace App\Tables\TaskItem;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($leftovers)
    {
        $leftoversArray = [];
        foreach ($leftovers as $key => $leftover) {
            $leftoversArray[] = [
                'id' => $leftover->id,
                'local_id' => $leftover->local_id,
                'batch' => $leftover->batch,
                'manufacture_date' => $leftover->manufacture_date,
                'bb_date' => $leftover->bb_date,
                'package' => $leftover->package->name,
                'has_condition' => $leftover->has_condition,
                'quantity' => [
                    'measurement_unit_quantity' => $leftover->quantity * $leftover->package->main_units_number,
                    'measurement_unit' => $leftover->goods->measurement_unit->name,
                    'package_quantity' => $leftover->quantity,
                    'package' => $leftover->package->name
                ],
                'container' => $leftover->container?->code,
                'processed_at' => $leftover->updated_at,
                'user' => $leftover->user->toArray(),
            ];
        }

        return TableCollectionResource::make($leftoversArray)->setTotal(count($leftoversArray));
    }

    /**
     * @return (array|string)[]|\Illuminate\Contracts\Database\Query\Expression|string
     *
     * @psalm-return \Illuminate\Contracts\Database\Query\Expression|array<int|string,
     *      array<int|string, mixed>|string>|string
     */
    #[\Override]
    public function renameFields($fieldName)
    {
        if ($fieldName == 'sku') {
            return DB::raw('goods.name');
        } else if ($fieldName == 'status') {
            return DB::raw('leftovers.status_id');
        }

        return $fieldName;
    }
}
