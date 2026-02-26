<?php

namespace App\Traits;

trait WorkspaceDataTrait
{
    public function scopeRelations($query): void
    {
        $query->with(['owner'])->withCount('companies');
    }

    public function scopeFilterCompaniesByUser($query, $userId): void
    {
        $query->whereHas('companies', function ($q) use ($userId) {
            $q->whereHas('users', function ($q) use ($userId) {
                $q->where('user_working_data.user_id', $userId);
            });
        });
    }
}
