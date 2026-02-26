<?php

namespace App\Models\Entities\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentLeftoverReservation extends Model
{
    public    $timestamps = false;
    protected $table      = 'document_leftover_reservations';
    protected $guarded    = [];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
