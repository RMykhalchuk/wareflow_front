<?php

namespace App\Traits;

use App\Models\Entities\Company\Company;
use App\Scopes\CompanyScope;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


//use in model protected array $cascadeDeletes = ['documents'];
trait CascadeSoftDeletes
{
    protected function getCascadeDeletes(): array
    {
        return property_exists($this, 'cascadeDeletes')
            ? $this->cascadeDeletes
            : [];
    }

    protected static function bootCascadeSoftDeletes()
    {
        static::deleting(function ($model) {
            foreach ($model->getCascadeDeletes() as $relationship) {
                if (!method_exists($model, $relationship)) {
                    continue;
                }

                $relation = $model->{$relationship}();

                if ($model->isForceDeleting()) {
                    // Force delete через chunk для великих обсягів
                    $relation->chunk(1000, function ($items) {
                        foreach ($items as $item) {
                            $item->forceDelete();
                        }
                    });
                } else {
                    // Bulk soft delete (ефективніше)
                    $relation->update(['deleted_at' => now()]);

                    // Або якщо потрібно тригерити події для кожного запису:
                    // $relation->each->delete();
                }
            }
        });

        static::restoring(function ($model) {
            foreach ($model->getCascadeDeletes() as $relationship) {
                if (!method_exists($model, $relationship)) {
                    continue;
                }

                // Bulk restore
                $model->{$relationship}()
                    ->withTrashed()
                    ->whereNotNull('deleted_at')
                    ->update(['deleted_at' => null]);

                // Або з подіями:
                // $model->{$relationship}()->withTrashed()->each->restore();
            }
        });
    }
}
