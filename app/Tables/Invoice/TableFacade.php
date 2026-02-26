<?php

namespace App\Tables\Invoice;

use App\Models\Entities\Contract\Invoice;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = [
            'company_provider' => function ($q): void {
                $q->select('companies.id')->addName();
            },
            'company_customer' => function ($q): void {
                $q->select('companies.id')->addName();
            },
        ];
        $invoices = Invoice::with($relationFields);

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $invoices);
    }
}
