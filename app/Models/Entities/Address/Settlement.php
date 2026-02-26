<?php

namespace App\Models\Entities\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Settlement extends Model
{
    use HasFactory;

    protected $table = '_d_settlements';

    protected $guarded = [];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Region>
     */
    public function region(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }
}
