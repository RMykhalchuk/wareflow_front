<?php

namespace App\Tables\Table;

abstract class AbstractFormatTableData
{
    abstract public function formatData($model);

    /**
     * @param (array|string)[]|string $fieldName
     *
     * @psalm-param array<int|string, array<int|string, mixed>|string>|string $fieldName
     * Description: Method for rename field from DB to front end.
     */
    public function renameFields(array|string $fieldName)
    {
        return $fieldName;
    }

    /**
     * @param (array|string)[]|string $fieldName
     *
     * @psalm-param array<int|string, array<int|string, mixed>|string>|string $fieldName
     *  Description: Method for filter data in relations.
     */
    public function relationsByField(string|array $fieldName)
    {
        return $fieldName;
    }

    /**
     * @return string
     *
     * @psalm-return 'name'
     * Description: Method for revert rename selects.
     */
    public function relationsSelectByField($relationName)
    {
        return 'name';
    }

    /**
     * @param (array|string)[]|string $fieldName
     *
     * @psalm-param array<int|string, array<int|string, mixed>|string>|string $fieldName
     * Description: Method for complex relations, such as polymorph relations.
 */
    public function joinsByField(array|string $fieldName, $model)
    {
        return $model;
    }
}
