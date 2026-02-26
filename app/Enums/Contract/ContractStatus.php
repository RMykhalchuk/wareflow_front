<?php

namespace App\Enums\Contract;

enum ContractStatus: int
{
    public const CREATED = 0;
    public const PENDING_SIGN = 2;
    public const SIGNED_ALL = 3;
    public const TERMINATED = 4;
    public const DECLINE = 5;
    public const DECLINE_CONTRACTOR = 6;
}
