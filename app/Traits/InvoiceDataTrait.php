<?php

namespace App\Traits;

use App\Enums\Invoice\InvoiceStatus;
use App\Models\User;

trait InvoiceDataTrait
{
    public function getType($invoice): string
    {
        return $this->isOutgoing($invoice) ? 'Вихідний' : 'Вхідний';
    }

    public function getStatusName($invoice): string
    {
        $isOutgoing = $this->isOutgoing($invoice);

        $statusNames = [
            InvoiceStatus::PENDING_OF_PAY->value => $isOutgoing
                ? 'Очікує на оплату контрагентом'
                : 'Очікує на вашу оплату',

            InvoiceStatus::PAYED->value => $isOutgoing
                ? 'Оплачено контрагентом'
                : 'Оплачено вами',

            InvoiceStatus::REJECTED->value => $isOutgoing
                ? 'Відхилено контрагентом'
                : 'Відхилено вами',
        ];

        return $statusNames[$invoice->status_id] ?? '';
    }

    private function isOutgoing($invoice): bool
    {
        return $invoice->creator_company_id == User::currentCompany();
    }
}
