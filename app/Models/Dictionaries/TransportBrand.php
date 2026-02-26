<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class TransportBrand extends Model
{
    use HasFactory;

    protected $table = '_d_transport_brands';

    public $timestamps = false;

    protected $guarded = [];
    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<TransportModel>
     */
    public function models(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransportModel::class, 'brand_id', 'id');
    }
}
