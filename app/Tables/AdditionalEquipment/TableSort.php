<?php

namespace App\Tables\AdditionalEquipment;

use App\Tables\Table\AbstractTableSort;

final class TableSort extends AbstractTableSort
{
    #[\Override]
    public function getSortedData($model)
    {
        if (isset($_GET['sortdatafield']) && $_GET['sortorder'] != '') {
            $sortDataField = $this->formatTableData->renameFields($_GET['sortdatafield']);
            $sortOrder = $_GET['sortorder'];

            if ($_GET['sortdatafield'] == 'model') {
                $model = $model
                    ->orderBy($sortDataField, $sortOrder)
                    ->select('*');

            } elseif ($_GET['sortdatafield'] == 'dnz') {
                $model = $model->orderBy('license_plate', $sortOrder);

            } elseif ($_GET['sortdatafield'] == 'company') {
                $model = $model
                   ->orderBy('company_with_details.name', $sortOrder);

            } elseif ($_GET['sortdatafield'] == 'typeLoad') {
                $model = $model
                    ->orderBy('methods.new_download_methods', $sortOrder);

            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }
        }
        return $model;
    }
}
