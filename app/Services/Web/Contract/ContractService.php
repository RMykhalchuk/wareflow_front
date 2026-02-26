<?php

namespace App\Services\Web\Contract;

use App\Enums\Contract\ContractStatus;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Contract\Contract;
use App\Models\Entities\Contract\ContractComment;
use App\Models\Entities\Contract\Regulation;
use App\Traits\ContractDataTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ContractService implements ContractServiceInterface
{
    use ContractDataTrait;

    public function getAllContracts(): \Illuminate\Database\Eloquent\Collection
    {
        return Contract::all();
    }

    public function getContractCreationData(): array
    {
        $companies = Company::filterByWorkspace()->select('companies.id')->addName()->get();
        $contractId = Contract::count() + 1;
        $regulations = Regulation::currentWorkspace()->where('parent_id', null)->where('draft', 0)->get();
        $typePallets = $this->typePallets;

        return compact('companies', 'contractId', 'regulations', 'typePallets');
    }

    public function getContractDisplayData(Contract $contract): array
    {
        $regulations = Regulation::currentWorkspace()->where('parent_id', null)->get();
        $typePallets = $this->typePallets;

        if ($contract->creator_company_id === Auth::user()->workingData->company_id) {
            $side = 'вихідного';
        } else {
            $side = 'вхідного';
        }

        $sideName = $this->getSideName($contract);
        $roleName = $this->getRoleName($contract);
        $typeName = $this->getTypeName($contract);
      //  $userSide = $this->getSide($contract);

        $contract->load([
                            'company' => function ($q) {
                                $q->select('companies.id')->addName();
                            },
                            'counterparty' => function ($q) {
                                $q->select('companies.id')->addName();
                            },
                            'comments.company' => function ($q) {
                                $q->select('companies.id')->addName();
                            },
                            'company_regulation.parent',
                            'counterparty_regulation.parent'
                        ]);

//        if ($userSide) {
//            $ownRegulation = $contract->company_regulation;
//            $contractorRegulation = $contract->counterparty_regulation;
//        } else {
//            $ownRegulation = $contract->counterparty_regulation;
//            $contractorRegulation = $contract->company_regulation;
//        }

        $ownRegulation = $contract->counterparty_regulation;
        $contractorRegulation = $contract->company_regulation;

        if ($ownRegulation) {
            $ownRegulation['settings'] = $this->translitPaletName(json_decode($ownRegulation->settings, true));
        }
        if ($contractorRegulation) {
            $contractorRegulation['settings'] = $this->translitPaletName(json_decode($contractorRegulation->settings, true));
        }

        return compact(
            'contract',
            'side',
            'sideName',
            'roleName',
            'typeName',
            'userSide',
            'ownRegulation',
            'contractorRegulation',
            'regulations',
            'typePallets'
        );
    }

    public function createContract(array $data): Contract
    {
        $contractData = $this->prepareContractData($data);
        $regulationId = $this->handleRegulationData($data);

        $contractData['company_regulation_id'] = $regulationId;
        $contractData['status'] = ContractStatus::CREATED;
        $contractData['created_at'] = Carbon::now();

        return Contract::create($contractData);
    }

    public function updateContract(Contract $contract, array $data): Contract
    {
        if (!$this->canModifyContract($contract)) {
            throw new \Exception('Дозволено редагування контрактів лише з статусом Створено');
        }

        $contractData = $this->prepareContractData($data);
        $regulationId = $this->handleRegulationData($data);

        $contractData['status'] = ContractStatus::CREATED;
        $contractData['created_at'] = Carbon::now();
        $contractData['company_regulation_id'] = $regulationId;

        $contract->fill($contractData);
        $contract->save();

        return $contract;
    }

    public function deleteContract(Contract $contract): bool
    {
        if (!$this->canModifyContract($contract)) {
            throw new \Exception('Дозволено видалення контрактів лише з статусом Створено');
        }

        return $contract->delete();
    }

    public function changeContractStatus(int $contractId, int $statusId, array $additionalData = []): bool
    {
        $newData = ['status' => $statusId];

        if ($statusId === ContractStatus::TERMINATED) {
            $newData['termination_reasons'] = $additionalData['termination_reasons'] ?? null;
        }

        if ($statusId === ContractStatus::PENDING_SIGN) {
            if (!isset($additionalData['counterparty_regulation_id'])) {
                throw new \Exception('Для підпису необхідно обрати регламент');
            }
            $newData['signed_at_counterparty'] = Carbon::now();
            $newData['counterparty_regulation_id'] = $additionalData['counterparty_regulation_id'];
        }

        if ($statusId === ContractStatus::SIGNED_ALL) {
            $newData['signed_at'] = Carbon::now();
        }

        if (in_array($statusId, [ContractStatus::DECLINE, ContractStatus::DECLINE_CONTRACTOR])) {
            $newData['decline_reasons'] = $additionalData['decline_reasons'] ?? null;
        }

        return Contract::where('id', $contractId)->update($newData);
    }

    public function createComment(array $data): ContractComment
    {
        return ContractComment::create($data);
    }

    public function canModifyContract(Contract $contract): bool
    {
        return $contract->status === ContractStatus::CREATED;
    }

    public function getContractEditData(Contract $contract): array
    {
        $companies = Company::filterByWorkspace()->select('companies.id')->addName()->get();
        $regulations = Regulation::currentWorkspace()->where('parent_id', null)->where('draft', 0)->get();
        $typePallets = $this->typePallets;

        $contract->load([
                            'company' => function ($q) {
                                $q->select('companies.id')->addName();
                            },
                            'counterparty' => function ($q) {
                                $q->select('companies.id')->addName();
                            },
                            'comments.company' => function ($q) {
                                $q->select('companies.id')->addName();
                            },
                            'company_regulation.parent',
                            'counterparty_regulation.parent'
                        ]);

        return compact('companies', 'regulations', 'typePallets', 'contract');
    }

    private function prepareContractData(array $data): array
    {
        return array_diff_key($data, array_flip(['_token', 'consideration_send', 'regulation_data', 'company_regulation_id']));
    }

    private function handleRegulationData(array $data): ?int
    {
        $regulationId = $data['company_regulation_id'] ?? null;
        $regulationData = $data['regulation_data'] ?? null;

        if ($regulationId && is_array($regulationData)) {
            Regulation::where('id', $regulationId)->update($regulationData);
        } elseif (!$regulationId && is_array($regulationData)) {
            $regulation = Regulation::create($regulationData);
            $regulationId = $regulation->id;
            Regulation::fixTree();
        }

        return $regulationId;
    }
}
