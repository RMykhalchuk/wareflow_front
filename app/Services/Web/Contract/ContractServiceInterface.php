<?php

namespace App\Services\Web\Contract;

use App\Models\Entities\Contract\Contract;
use App\Models\Entities\Contract\ContractComment;

interface ContractServiceInterface
{
    /**
     * Get all contracts
     */
    public function getAllContracts(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get contract creation data
     */
    public function getContractCreationData(): array;

    /**
     * Get contract display data
     */
    public function getContractDisplayData(Contract $contract): array;

    /**
     * Create a new contract
     */
    public function createContract(array $data): Contract;

    /**
     * Update an existing contract
     */
    public function updateContract(Contract $contract, array $data): Contract;

    /**
     * Delete a contract
     */
    public function deleteContract(Contract $contract): bool;

    /**
     * Change contract status
     */
    public function changeContractStatus(int $contractId, int $statusId, array $additionalData = []): bool;

    /**
     * Create a comment for contract
     */
    public function createComment(array $data): ContractComment;

    /**
     * Check if contract can be modified
     */
    public function canModifyContract(Contract $contract): bool;

    /**
     * Get contract edit data
     */
    public function getContractEditData(Contract $contract): array;
}
