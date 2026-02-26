<?php

namespace App\Tables\TransportRequest;

use App\Http\Resources\Web\TableCollectionResource;
use App\Models\Entities\TransportPlanning\TransportPlanning;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    private array $dataFields;



    public function __construct()
    {
        $this->dataFields = (new TransportPlanning())->getFieldsByType('zapyt_na_transport');
    }
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($documents)
    {
        $documentsArr = [];
        for ($i = 0; $i < count($documents); $i++) {
            $documentsArr[] = [];

            $docData = $documents[$i]->allBlocksToArray();


            $documentsArr[$i]['id'] = $documents[$i]->id;

            $documentsArr[$i]['loading'] = [
                'company' => $docData['header'][$this->dataFields['companyProviderField']],
                'location' => $docData['header'][$this->dataFields['loadingWarehouseField']],
                'date' => $docData['header'][$this->dataFields['loadingDate']][0],
                'start_at' => $docData['header'][$this->dataFields['loadingDate']][1],
                'end_at' => $docData['header'][$this->dataFields['loadingDate']][2]
            ];

            $documentsArr[$i]['unloading'] = [
                'company' => $docData['header'][$this->dataFields['companyCustomerField']],
                'location' => $docData['header'][$this->dataFields['unloadingWarehouseField']],
                'date' => $docData['header'][$this->dataFields['unloadingDate']][0],
                'start_at' => $docData['header'][$this->dataFields['unloadingDate']][1],
                'end_at' => $docData['header'][$this->dataFields['unloadingDate']][2]
            ];

            $documentsArr[$i]['pallet'] = $docData['header'][$this->dataFields['pallet']] ;
            $documentsArr[$i]['weight'] = $docData['header'][$this->dataFields['weight']] ;
        }

        return TableCollectionResource::make(array_values($documentsArr))->setTotal($documents->total());
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
        if ($fieldName == 'type') {
            $fieldName = DB::raw('documents.id');
        } elseif ($fieldName == 'loading') {
            $fieldName = DB::raw("CONCAT(JSON_EXTRACT(data, '$.header."
                . $this->dataFields['companyProviderField'] . "'), ' ', JSON_EXTRACT(data, '$.header."
                . $this->dataFields['loadingWarehouseField'] . "'), ' ', JSON_EXTRACT(data, '$.header."
                . $this->dataFields['loadingDate'] . "[0]'), ' ', JSON_EXTRACT(data, '$.header."
                . $this->dataFields['loadingDate'] . "[1]'), ' ', JSON_EXTRACT(data, '$.header."
                . $this->dataFields['loadingDate'] . "[2]'))");
        } elseif ($fieldName == 'unloading') {
            $fieldName = DB::raw("CONCAT(JSON_EXTRACT(data, '$.header."
                . $this->dataFields['companyCustomerField'] . "'), ' ', JSON_EXTRACT(data, '$.header."
                . $this->dataFields['unloadingWarehouseField'] . "'), ' ', JSON_EXTRACT(data, '$.header."
                . $this->dataFields['unloadingDate'] . "[0]'), ' ', JSON_EXTRACT(data, '$.header."
                . $this->dataFields['unloadingDate'] . "[1]'), ' ', JSON_EXTRACT(data, '$.header."
                . $this->dataFields['unloadingDate'] . "[2]'))");
        }

        return $fieldName;
    }
}
