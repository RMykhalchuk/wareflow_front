<?php

namespace App\Tables\Transport;

use App\Tables\Table\AbstractTableSort;

final class TableSort extends AbstractTableSort
{
    #[\Override]
    public function getSortedData($model)
    {
        if (isset($_GET['sortdatafield']) && $_GET['sortorder'] != '') {
            $sortDataField = $this->formatTableData->renameFields($_GET['sortdatafield']);
            $sortOrder = $_GET['sortorder'];

            if ($sortDataField == 'company') {
                $model = $model
                    ->orderBy('company_with_details.name', $sortOrder);

            } elseif ($sortDataField == 'type') {
                $model = $model
                    ->orderBy('_d_transport_types.name', $sortOrder);

            } elseif ($sortDataField == 'category') {
                $model = $model
                    ->orderBy('_d_transport_categories.name', $sortOrder);

            } elseif ($_GET['sortdatafield'] == 'defaultDriver') {
                $model = $model
                    ->orderBy($sortDataField, $sortOrder);

            } elseif ($sortDataField == 'licensePlate') {
                $model = $model->orderBy('license_plate', $sortOrder);

            } elseif ($_GET['sortdatafield'] == 'model') {
                $model = $model
                    ->orderBy($sortDataField, $sortOrder);

            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }
        }
        return $model;
    }
}
