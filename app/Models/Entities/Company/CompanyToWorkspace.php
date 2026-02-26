<?php

namespace App\Models\Entities\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class CompanyToWorkspace extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];
}
