<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait RegulationTrait
{
    public function scopeAddContractsCount($q): void
    {
        $q->addSelect(DB::raw('(SELECT COUNT(*) FROM contracts WHERE contracts.company_regulation_id = regulations.id OR contracts.counterparty_regulation_id = regulations.id) as contracts_count'));
    }
}
