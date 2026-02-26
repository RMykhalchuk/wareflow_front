<?php

namespace App\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class TableFilter
{
    public function __construct(private AbstractTableSort $tableSort, private AbstractFormatTableData $formatTableData)
    {
    }

    //Library rewrote code with custom queries
    /**
     * @param (\Closure|string)[] $relationFields
     *
     * @psalm-param array{0?: string, 1?: string, transport?: \Closure(mixed):void, 2?: string, 3?: 'category'|'download_zone'|'user', company_provider?: \Closure(mixed):void, company_customer?: \Closure(mixed):void, 'goods.packages'?: \Closure(mixed):void, 4?: 'status', company?: \Closure(mixed):void, counterparty?: \Closure(mixed):void} $relationFields
     * @psalm-param Builder<\App\Models\Entities\Transport\AdditionalEquipment>|Builder<\App\Models\Entities\Barcode>|Builder<\App\Models\Entities\Container\ContainerByDocument>|Builder<\App\Models\Entities\Contract\Contract>|Builder<\App\Models\Entities\Document\Document>|Builder<\App\Models\Entities\Contract\Invoice>|Builder<\App\Models\Entities\Leftover\Leftover>|Builder<\App\Models\Entities\Package>|Builder<\App\Models\Dictionaries\Register>|Builder<\App\Models\Entities\Goods\GoodsByDocument>|Builder<\App\Models\Entities\Transport\Transport>|Builder<\App\Models\Entities\TransportPlanning\TransportPlanning>|Builder<\App\Models\Entities\WarehouseComponents\Warehouse> $model
     */
    public function filter(array $relationFields, Builder $model)
    {

        $currentPage = array_key_exists('pagenum', $_GET) ? $_GET['pagenum'] + 1 : 1;
        $pageSize = array_key_exists('pagesize', $_GET) ? $_GET['pagesize'] : 15;

        if (isset($_GET['filterscount'])) {
            $filtersCount = $_GET['filterscount'];
            if ($filtersCount > 0) {
                $addedJoins = [];
                for ($i = 0; $i < $filtersCount; $i++) {
                    if (!in_array($_GET['filterdatafield' . $i], $addedJoins)) {
                        $model = $this->formatTableData->joinsByField($_GET['filterdatafield' . $i], $model);
                        $addedJoins[] = $_GET['filterdatafield' . $i];
                    }
                }
            }
        }

        $model->where(function ($m) use ($model, $relationFields) {
            if (isset($_GET['filterscount'])) {
                $filtersCount = $_GET['filterscount'];

                if ($filtersCount > 0) {
                    for ($i = 0; $i < $filtersCount; $i++) {
                        if (isset($_GET['filterdatafield' . $i + 1]) && $_GET['filterdatafield' . $i] == $_GET['filterdatafield' . $i + 1]) {
                            $m = $m->where(function ($query) use ($m, $relationFields, $i) {
                                $query = $this->getFilteredData($query, $relationFields, $i);
                                return $this->getFilteredData($query, $relationFields, $i + 1);
                            });
                        } elseif (!isset($_GET['filterdatafield' . $i - 1]) || isset($_GET['filterdatafield' . $i - 1]) && $_GET['filterdatafield' . $i] != $_GET['filterdatafield' . $i - 1]) {
                            $m = $this->getFilteredData($m, $relationFields, $i);
                        }
                    }
                }
            }
        });


        $model = $this->tableSort->getSortedData($model);

        $model = $model->paginate($pageSize, page: $currentPage);

        return $this->formatTableData->formatData($model);
    }

    /**
     * @psalm-param int<0, max> $iterationNumber
     */
    private function getFilteredData($model, $relationFields, int $iterationNumber)
    {
        $filterValue = $_GET['filtervalue' . $iterationNumber];
        $filterCondition = $_GET['filtercondition' . $iterationNumber];
        $filterDataField = $this->formatTableData->renameFields($_GET['filterdatafield' . $iterationNumber]);
        $whereOperator = $this->getWhereOperator($_GET['filteroperator' . $iterationNumber]);
        $relationWhereOperator = "{$whereOperator}Has";

        $relation = $this->formatTableData->relationsByField($_GET['filterdatafield' . $iterationNumber]);

        $condition = '=';
        $specialCondition = false;
        $caseSensitiveCondition = false;

        $this->getFilterConditions($filterCondition, $filterValue, $condition, $caseSensitiveCondition, $specialCondition);

        if ($specialCondition) {
            if (in_array($filterDataField, $relationFields)) {
                $filterDataField .= '_id';
            }
            $model = $model->when($condition == 'IS NULL', function ($query) use ($filterDataField, $whereOperator) {
                $query->$whereOperator(function ($query2) use ($filterDataField) {
                    $query2->whereNull($filterDataField)->orWhere($filterDataField, '');
                });
            });

            $model = $model->when($condition == 'IS NOT NULL', function ($query) use ($filterDataField, $whereOperator) {
                $query->$whereOperator(function ($query2) use ($filterDataField) {
                    $query2->whereNotNull($filterDataField)->orWhere($filterDataField, '');
                });
            });
        } elseif ($caseSensitiveCondition) {
            $model = $model->when(in_array($relation, $relationFields), function ($query) use ($condition, $filterValue, $whereOperator, $relation, $relationWhereOperator) {
                $query->$relationWhereOperator($relation, function (Builder $query) use ($condition, $filterValue, $whereOperator, $relation) {
                    $query->where(DB::raw('BINARY `' . $this->formatTableData->relationsSelectByField($relation) . '`'), $condition, $filterValue);
                });
            }, function ($query) use ($filterDataField, $condition, $filterValue, $whereOperator, $relation, $relationWhereOperator) {
                $query->$relationWhereOperator($relation, function (Builder $query) use ($filterDataField, $condition, $filterValue, $whereOperator) {
                    $query->where(DB::raw("BINARY $filterDataField"), $condition, $filterValue);
                });
            });
        } else {
            $model = $model->when(in_array($relation, $relationFields), function ($query) use ($relation, $condition, $filterValue, $whereOperator, $relationWhereOperator) {
                $query->$relationWhereOperator($relation, function (Builder $query) use ($condition, $filterValue, $whereOperator, $relation) {
                    $query->where(DB::raw($this->formatTableData->relationsSelectByField($relation)), $condition, $filterValue);
                });
            }, function ($query) use ($filterDataField, $condition, $filterValue, $whereOperator) {
                $query->$whereOperator($filterDataField, $condition, $filterValue);
            });
        }
        return $model;
    }

    private function getWhereOperator(string|array $filterOperator): string
    {
        if ($filterOperator != 0) {
            $query = 'orWhere';
        } else {
            $query = 'where';
        }

        return $query;
    }

    private function getFilterConditions(string|array $filterCondition, string|array &$filterValue, string &$condition, bool &$caseSensitiveCondition, bool &$specialCondition): void
    {
        switch ($filterCondition) {
            case 'CONTAINS':
                $condition = 'like';
                $filterValue = "%{$filterValue}%";
                break;
            case 'CONTAINS_CASE_SENSITIVE':
                $condition = 'like';
                $filterValue = "%{$filterValue}%";
                $caseSensitiveCondition = true;
                break;
            case 'DOES_NOT_CONTAIN':
                $condition = 'not like';
                $filterValue = "%{$filterValue}%";
                break;
            case 'DOES_NOT_CONTAIN_CASE_SENSITIVE':
                $condition = 'not like';
                $filterValue = "%{$filterValue}%";
                $caseSensitiveCondition = true;
                break;
            case 'NOT_EQUAL':
                $condition = '!=';
                break;
            case 'GREATER_THAN':
                $condition = '>';
                break;
            case 'LESS_THAN':
                $condition = '<';
                break;
            case 'GREATER_THAN_OR_EQUAL':
                $condition = '>=';
                break;
            case 'LESS_THAN_OR_EQUAL':
                $condition = '<=';
                break;
            case 'STARTS_WITH':
                $condition = 'LIKE';
                $filterValue = "{$filterValue}%";
                break;
            case 'STARTS_WITH_CASE_SENSITIVE':
                $condition = 'LIKE';
                $filterValue = "{$filterValue}%";
                $caseSensitiveCondition = true;
                break;
            case 'ENDS_WITH':
                $condition = 'LIKE';
                $filterValue = "%{$filterValue}";
                break;
            case 'ENDS_WITH_CASE_SENSITIVE':
                $condition = 'LIKE';
                $filterValue = "%{$filterValue}";
                $caseSensitiveCondition = true;
                break;
            case 'EQUAL_CASE_SENSITIVE':
                $caseSensitiveCondition = true;
                break;
            case 'NULL':
            case 'EMPTY':
                $condition = 'IS NULL';
                $specialCondition = true;
                break;
            case 'NOT_NULL':
            case 'NOT_EMPTY':
                $condition = 'IS NOT NULL';
                $specialCondition = true;
                break;
        }
    }
}
