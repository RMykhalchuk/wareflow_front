<?php

namespace App\Tables\TransportPlaning;

use App\Models\Entities\TransportPlanning\TransportPlanningDocument;
use App\Tables\Table\TableFilter;
use Illuminate\Support\Facades\DB;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = [];

        //TODO переробити дати
        $transportPlaningDocuments = TransportPlanningDocument::select([
            DB::raw("(CASE WHEN CURRENT_DATE() > DATE_FORMAT(download_start, '%Y-%m-%d') THEN
             DATE_FORMAT(unloading_start, '%Y-%m-%d') ELSE DATE_FORMAT(download_start, '%Y-%m-%d') END) as date"),
            DB::raw("ANY_VALUE((CASE DATE_FORMAT((CASE WHEN CURRENT_DATE() > DATE_FORMAT(download_start, '%Y-%m-%d') THEN
             DATE_FORMAT(unloading_start, '%Y-%m-%d') ELSE DATE_FORMAT(download_start, '%Y-%m-%d') END), '%w') WHEN 0 THEN
              'Неділя' WHEN 1 THEN 'Понеділок' WHEN 2 THEN 'Вівторок' WHEN 3 THEN 'Середа' WHEN 4 THEN 'Четвер' WHEN 5 THEN
               'П\'ятниця' WHEN 6 THEN 'Субота' END)) as weekday"),
            DB::raw('COUNT(*) as tp_count')
        ])->groupBy('date');

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $transportPlaningDocuments);
    }
}
