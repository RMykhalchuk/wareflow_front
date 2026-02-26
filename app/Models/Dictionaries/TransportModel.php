<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class TransportModel extends Model
{
    use HasFactory;

    protected $table = '_d_transport_models';

    public $timestamps = false;

    protected $guarded = [];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<TransportBrand>
     */
    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransportBrand::class, 'brand_id');
    }
}
