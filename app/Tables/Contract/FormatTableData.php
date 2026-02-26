<?php

namespace App\Tables\Contract;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use App\Traits\ContractDataTrait;

final class FormatTableData extends AbstractFormatTableData
{
    use ContractDataTrait;

    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($contracts)
    {
        $contractsArr = [];
        for ($i = 0; $i < count($contracts); $i++) {
            $contractsArr[] = $contracts[$i]->toArray();

            $contractsArr[$i]['id'] = $contracts[$i]->id;

            $contractsArr[$i]['number'] = $contracts[$i]->id;

            $contractsArr[$i]['yourCompany'] = $contracts[$i]->company->name;
            $contractsArr[$i]['yourCompanyId'] = $contracts[$i]->company->id;

            $contractsArr[$i]['contractor'] = $contracts[$i]->counterparty->name;
            $contractsArr[$i]['contractorId'] = $contracts[$i]->counterparty->id;

            $contractsArr[$i]['inputOutput'] = $this->getSideName($contracts[$i]);

            $contractsArr[$i]['type'] = $this->getTypeName($contracts[$i]);

            $contractsArr[$i]['status'] = $this->getStatusName($contracts[$i]);
        }

        return TableCollectionResource::make(array_values($contractsArr))->setTotal($contracts->total());
    }
}
