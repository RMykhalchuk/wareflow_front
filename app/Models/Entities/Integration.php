<?php

namespace App\Models\Entities;

use App\Traits\CompanySeparation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class Integration extends Model
{
    protected $guarded = [];

    use CompanySeparation;

    public static function store(\Illuminate\Http\Request $request)
    {
        $data = $request->except(['_token']);

        $data['key'] = Str::random(60);

        $integration = Integration::create($data);

        return $integration->id;
    }
}
