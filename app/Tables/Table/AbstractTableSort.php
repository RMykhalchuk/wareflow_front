<?php

namespace App\Tables\Table;

abstract class AbstractTableSort
{
    public function __construct(protected AbstractFormatTableData $formatTableData) {}

    abstract public function getSortedData($model);

    public function existsInFilter(string $fieldName): bool
    {
        if (isset($_GET['filterscount'])) {
            $filtersCount = $_GET['filterscount'];
            if ($filtersCount > 0) {
                for ($i = 0; $i < $filtersCount; $i++) {
                    if ($_GET['filterdatafield' . $i] === $fieldName) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
