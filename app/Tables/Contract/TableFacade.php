<?php

namespace App\Tables\Contract;

use App\Models\Entities\Contract\Contract;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = [
            'company'      => function ($q): void {
                $q->select('companies.id')->addName();
            },
            'counterparty' => function ($q): void {
                $q->select('companies.id')->addName();
            },
        ];
        $transports = Contract::with($relationFields);

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $transports);
    }
}
