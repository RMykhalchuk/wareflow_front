<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FilterByCompany
{
    protected static bool $disableCompanyScope = false;

    public static function bootHasWorkspaceAndCompanyScope(): void
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if (!static::$disableCompanyScope) {
                $builder->where('creator_company_id', Auth::user()?->company_id);
            }
        });
    }

    public static function withoutCompanyScope(callable $callback)
    {
        static::$disableCompanyScope = true;
        $result = $callback();
        static::$disableCompanyScope = false;

        return $result;
    }
}
