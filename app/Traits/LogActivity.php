<?php

namespace App\Traits;

use App\Models\Entities\Logs\EntityLog;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait LogActivity
{
    public static function bootLogActivity(): void
    {
        static::created(function ($model) {
            self::logChange('created', $model);
        });

        static::updated(function ($model) {
            self::logChange('updated', $model);
        });

        static::deleted(function ($model) {
            self::logChange('deleted', $model);
        });
    }

    protected static function logChange(string $type, $model): void
    {
        try {
            EntityLog::create(
                [
                    'log_type' => $type,
                    'model_type' => strtolower(class_basename($model)),
                    'model_id' => $model->id,
                    'user_id' => auth()->id(),
                    'creator_company_id' => auth()->user()->creator_company_id ?? null,
                    'ip_address' => request()->ip(),
                    'source' => request()->is('api/*') ? 'api' : 'web',
                ]);
        } catch (\Throwable $e) {
            // Не блокуємо роботу системи, якщо лог не записався
            \Log::error('Entity logging failed: ' . $e->getMessage());
        }
    }


    public function log(): HasOne
    {
        return $this->hasOne(EntityLog::class, 'model_id', 'id')->where('log_type', 'created');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(EntityLog::class, 'model_id', 'id');
    }
}
