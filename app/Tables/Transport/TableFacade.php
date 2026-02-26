<?php

namespace App\Tables\Transport;

use App\Models\Entities\Transport\Transport;
use App\Tables\Table\TableFilter;
use Illuminate\Support\Facades\DB;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['company', 'driver', 'model.brand'];

        $transports = Transport::with($relationFields)
            ->leftJoin('_d_transport_brands', 'transports.brand_id', '=', '_d_transport_brands.id')
            ->leftJoin('_d_transport_models', 'transports.model_id', '=', '_d_transport_models.id')
            ->leftJoin('_d_transport_categories', '_d_transport_categories.id', '=', 'transports.category_id')
            ->leftJoin('_d_transport_types', '_d_transport_types.id', '=', 'transports.type_id')
            ->leftJoin('users', 'transports.driver_id', '=', 'users.id')
            ->leftJoin(
                DB::raw("
                    (
                        SELECT companies.id,
                               CASE
                                   WHEN companies.company_type_id = 1 THEN CONCAT(physical_companies.first_name, ' ', physical_companies.surname)
                                   ELSE legal_companies.name
                               END AS name
                        FROM companies
                        LEFT JOIN physical_companies ON companies.company_id = physical_companies.id
                        LEFT JOIN legal_companies ON companies.company_id = legal_companies.id
                    ) AS company_with_details
                "),
                'transports.company_id',
                '=',
                'company_with_details.id'
            )
            ->select(
                DB::raw("CONCAT(_d_transport_brands.name, ' ', _d_transport_models.name)"),
                'transports.*'
            );

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $transports);
    }
}

