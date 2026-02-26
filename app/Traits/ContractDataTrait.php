<?php

namespace App\Traits;

use App\Enums\Contract\ContractRole;
use App\Enums\Contract\ContractStatus;
use App\Enums\Contract\ContractType;
use App\Models\User;

trait ContractDataTrait
{
    protected array $typePallets = [
        'evropaleta_120h80sm' => 'Європалета 120х80см',
        "amerikans'ka_paleta_120h100sm" => 'Американська палета 120х100см',
        'napivpaleta_60h80sm' => 'Напівпалета 60х80см',
        "fins'ka_paleta" => 'Фінська палета',
    ];

    public function getSideName($contract): string
    {
        return $this->isOutgoingContract($contract) ? 'Вихідний' : 'Вхідний';
    }

    public function getTypeName($contract): string
    {
        return match ($contract->type_id) {
            ContractType::TRADE_SERVICE => 'Договір на торгові послуги',
            ContractType::WAREHOUSE_SERVICE => 'Договір на складські послуги',
            ContractType::TRANSPORT_SERVICE => 'Договір на транспортні послуги',
            default => '',
        };
    }

    public function getStatusName($contract): string
    {
        return match ($contract->status) {
            ContractStatus::CREATED => 'Створено',
            ContractStatus::PENDING_SIGN => 'Очікує на підпис',
            ContractStatus::SIGNED_ALL => 'Підписано всіма',
            ContractStatus::TERMINATED => 'Розірвано',
            ContractStatus::DECLINE => 'Відхилено',
            ContractStatus::DECLINE_CONTRACTOR => 'Відхилено контрагентом',
            default => '',
        };
    }

    public function getRoleName($contract): string
    {
        return match ($contract->role) {
            ContractRole::CUSTOMER->value => 'Замовник',
            ContractRole::PROVIDER->value => 'Постачальник',
            default => '',
        };
    }

    public function isOutgoingContract($contract): bool
    {
        return $contract->creator_company_id === User::currentCompany();
    }

    public function translitPaletName(array $settings): array
    {
        if (!empty($settings['typePalet']) && isset($this->typePallets[$settings['typePalet']])) {
            $settings['typePalet'] = $this->typePallets[$settings['typePalet']];
        }

        return $settings;
    }
}
