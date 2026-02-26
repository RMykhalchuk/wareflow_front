<?php

namespace App\Tables\Invoice;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use App\Traits\InvoiceDataTrait;

final class FormatTableData extends AbstractFormatTableData
{
    use InvoiceDataTrait;

    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($invoices)
    {
        $invoiceArr = [];
        for ($i = 0; $i < count($invoices); $i++) {
            $invoiceArr[] = $invoices[$i]->toArray();

            $invoiceArr[$i]['number'] = $invoices[$i]?->category?->name;

            $invoiceArr[$i]['inputOutput'] = $this->getType($invoices[$i]);
            $invoiceArr[$i]['performer'] = $invoices[$i]->company_provider->name;
            $invoiceArr[$i]['receiver'] = $invoices[$i]->company_customer->name;
            $invoiceArr[$i]['date'] = $invoices[$i]->invoice_at;
            $invoiceArr[$i]['sum'] = $invoices[$i]->sum;

            $invoiceArr[$i]['status'] = $this->getStatusName($invoices[$i]);
        }

        return TableCollectionResource::make(array_values($invoiceArr))->setTotal($invoices->total());
    }
}
