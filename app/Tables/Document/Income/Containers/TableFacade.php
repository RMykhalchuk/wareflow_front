<?php

namespace App\Tables\Document\Income\Containers;

use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;
use Illuminate\Support\Facades\DB;

final class TableFacade
{
    public static function getFilteredData($document)
    {
        $relationFields = ['container'];

        $rows = IncomeDocumentLeftover::where('document_id', $document->id)
            ->select('container_id', DB::raw('MIN(local_id) as min_local_id'))
            ->groupBy('container_id')
            ->get();

        $localIds         = $rows->pluck('min_local_id', 'container_id')->toArray();
        $containerIdArray = array_keys($localIds);

        $containerRegisters = ContainerRegister::whereIn('id', $containerIdArray)
            ->select(['id', 'code', 'created_at']);

        $formatTable = (new FormatTableData())->setLocalIds($localIds);
        $tableSort   = new TableSort($formatTable);
        $filter      = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $containerRegisters);
    }
}
