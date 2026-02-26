<?php

namespace App\Enums\Invoice;

enum InvoiceStatus: int
{
    case PENDING_OF_PAY = 1;
    case PAYED = 2;
    case REJECTED = 3;
}
