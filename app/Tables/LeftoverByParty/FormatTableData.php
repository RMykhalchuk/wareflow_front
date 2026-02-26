<?php

namespace App\Tables\LeftoverByParty;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($leftovers)
    {
        $leftoversArr = [];
        $newLeftovers = collect();

        $leftovers->each(function ($item) use (&$newLeftovers) {
            $newItem = $newLeftovers->where('id', $item->id)->where('party', $item->party)->first();

            if (!is_null($newItem)) {
                //update all count, fill warehouse data
                $newItem['count'] += $item->count;
                $newItem['warehouses'] = array_merge($newItem['warehouses'], [[
                    'warehouse_name' => $item->warehouse_name,
                    'count' => $item->count,
                ]]);

                //update current item
                $newLeftovers->map(function ($item) use ($newItem) {
                    if ($item->id === $newItem->id) {
                        return $newItem;
                    }

                    return $item;
                });
            } else {
                $item['warehouses'] = [[
                    'warehouse_name' => $item->warehouse_name,
                    'count' => $item->count,
                ]];
                $newLeftovers->push($item);
            }
        });

        for ($i = 0; $i < count($newLeftovers); $i++) {
            $leftoversArr[] = $newLeftovers[$i]->toArray();
        }

        return TableCollectionResource::make(array_values($leftoversArr))->setTotal($newLeftovers->count());
    }

    /**
     * @return (array|string)[]|string
     *
     * @psalm-return array<int|string, array<int|string, mixed>|string>|string
     */
    #[\Override]
    public function renameFields($fieldName)
    {
        if ($fieldName == 'sku') {
            $fieldName = 'goods.name';
        } elseif ($fieldName == 'party') {
            $fieldName = 'leftovers.consignment';
        }

        return $fieldName;
    }
}
