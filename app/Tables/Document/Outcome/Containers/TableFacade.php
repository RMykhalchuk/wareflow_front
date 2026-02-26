<?php

namespace App\Tables\Document\Outcome\Containers;

use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\OutcomeDocumentLeftover;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;

final class TableFacade
{
    public static function getFilteredData($document)
    {
        $relationFields = ['container'];

        $containerIds = OutcomeDocumentLeftover::where('outcome_document_leftovers.document_id', $document->id)
            ->join('leftovers', 'leftovers.id', '=', 'outcome_document_leftovers.leftover_id')
            ->whereNotNull('leftovers.container_id')
            ->pluck('leftovers.container_id')
            ->unique()
            ->values();

        $localIds         = $containerIds->mapWithKeys(fn ($id, $idx) => [$id => $idx + 1])->toArray();
        $containerIdArray = $containerIds->toArray();

        $containerRegisters = ContainerRegister::whereIn('id', $containerIdArray)
            ->select(['id', 'code', 'created_at']);

        $formatTable = (new FormatTableData())->setLocalIds($localIds);
        $tableSort   = new TableSort($formatTable);
        $filter      = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $containerRegisters);
    }
}