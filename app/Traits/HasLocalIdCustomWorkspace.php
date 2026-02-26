<?php

namespace App\Traits;

trait HasLocalIdCustomWorkspace
{
    public static function bootHasLocalIdCustomWorkspace()
    {
        static::creating(function ($model) {
            $column = $model->getCreatorCompanyColumn(); // через метод, бо $this недоступне тут
            $max = static::where($column, $model->$column)->max('local_id');
            $model->local_id = $max ? $max + 1 : 1;
        });
    }

    public function getCreatorCompanyColumn(): string
    {
        return 'creator_company_id';
    }
}
