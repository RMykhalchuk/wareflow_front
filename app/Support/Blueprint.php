<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;

class Blueprint extends BaseBlueprint
{
    public function uuid($column = 'uuid')
    {
        return parent::uuid($column)->collation('ascii_general_ci');
    }
}
