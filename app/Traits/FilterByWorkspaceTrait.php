<?php

namespace App\Traits;

use App\Models\Entities\System\Workspace;
use Illuminate\Database\Eloquent\Builder;

trait FilterByWorkspaceTrait
{
    protected static bool $disableWorkspaceScope = false;

    /**
     *  Global Scope Workspace while boot (only rows from current workspace).
     */
    public static function bootFilterByWorkspaceTrait(): void
    {
        static::addGlobalScope('workspace', function (Builder $builder) {
            if (!static::$disableWorkspaceScope) {
                $builder->where('workspace_id', Workspace::current());
            }
        });
    }

    /**
     * Turn of Global Scope
     *
     * @param Builder $query
     * @return Builder
     */
    public static function disableWorkspaceScope(Builder $query): Builder
    {
        static::$disableWorkspaceScope = true;

        return $query;
    }

    /**
     * Return without global scope (all rows from all workspaces)
     *
     * @param Builder $query
     * @return Builder
     */
    public static function scopeWithoutWorkspace(Builder $query): Builder
    {
        return $query->withoutGlobalScope('workspace');
    }
}

