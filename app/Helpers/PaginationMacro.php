<?php

namespace App\Helpers;

use App\Providers\AppServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

final class PaginationMacro
{
    public static function changePagination(AppServiceProvider $provider): void
    {
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') use ($provider) {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $provider->forPage($page, $perPage),
                $total ?: $provider->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
