<?php

namespace App\Enums\Obligation;

enum ObligationServiceType: int
{
    case TRANSPORTATION = 1;
    case WAREHOUSE = 2;
    case CUSTOMS_BROKERAGE = 3;
}
