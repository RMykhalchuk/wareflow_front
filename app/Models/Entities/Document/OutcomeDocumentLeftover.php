<?php

namespace App\Models\Entities\Document;

use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;
use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutcomeDocumentLeftover extends Model
{
    use HasUuid;

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $guarded = [];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function leftover(): BelongsTo
    {
        return $this->belongsTo(Leftover::class, 'leftover_id');
    }

}
