<?php

namespace App\Models\Entities\Document;


use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Package;
use App\Models\User;
use App\Services\Web\Auth\AuthContextService;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeDocumentLeftover extends Model
{
    use HasUuid;

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (IncomeDocumentLeftover $model) {
            // якщо local_id вже встановлено вручну — не чіпаємо
            if (!empty($model->local_id)) {
                return;
            }

            // Блокуємо останній рядок і беремо max local_id
            $last = static::where('document_id', $model->document_id)
                ->orderByDesc('local_id')
                ->lockForUpdate()
                ->first();

            $model->local_id = $last?->local_id ? $last->local_id + 1 : 1;
        });
    }


    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(ContainerRegister::class, 'container_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
