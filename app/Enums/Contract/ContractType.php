<?php

namespace App\Enums\Contract;

enum ContractType: int
{
    public const TRADE_SERVICE = 0;
    public const WAREHOUSE_SERVICE = 1;
    public const TRANSPORT_SERVICE = 2;
}
