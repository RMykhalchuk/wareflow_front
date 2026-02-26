<?php

namespace App\Tables\Registers;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($registers)
    {
        $formatedArray = [];
        for ($i = 0; $i < count($registers); $i++) {
            $formatedArray[] = $registers[$i]->toArray();
            $formatedArray[$i]['storekeeper'] = $formatedArray[$i]['storekeeper'] ?
                $formatedArray[$i]['storekeeper']['surname'] . ' ' .
                mb_strtoupper(mb_substr($formatedArray[$i]['storekeeper']['name'], 0, 1)) . '.' : null;

            $formatedArray[$i]['manager'] = $formatedArray[$i]['manager'] ?
                $formatedArray[$i]['manager']['surname'] . ' ' .
                mb_strtoupper(mb_substr($formatedArray[$i]['manager']['name'], 0, 1)) . '.' : null;

            $formatedArray[$i]['status_key'] = $formatedArray[$i]['status']['key'];
            $formatedArray[$i]['status'] = $formatedArray[$i]['status']['name'];
            $formatedArray[$i]['download_method'] = $formatedArray[$i]['download_method']['name'] ?? null;
            $formatedArray[$i]['download_zone'] = $formatedArray[$i]['download_zone']['name'] ?? null;
        }

        return TableCollectionResource::make(array_values($formatedArray))->setTotal($registers->total());
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
        if ($fieldName == 'palet') {
            $fieldName = DB::raw("CONCAT(mono_pallet,'/',collect_pallet)");
        }
        return $fieldName;
    }
}
