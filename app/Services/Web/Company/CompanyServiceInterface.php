<?php

namespace App\Services\Web\Company;

use App\Http\Requests\Web\Company\LegalCompanyRequest;
use App\Http\Requests\Web\Company\PhysicalCompanyRequest;
use App\Http\Resources\Web\CompanyResource;
use App\Models\Entities\Company\Company;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyServiceInterface
{
    public function getAllCompanies(): array;

    public function getCreateFormData(): array;

    public function createLegalCompany(LegalCompanyRequest $request): Company;

    public function createPhysicalCompany(PhysicalCompanyRequest $request): Company;

    public function getCompanyShowData(Company $company): array;

    public function getEditFormData(Company $company): array;

    public function updateLegalCompany(LegalCompanyRequest $request, Company $company): Company;

    public function updatePhysicalCompany(PhysicalCompanyRequest $request, Company $company): Company;

    public function deleteCompany(Company $company): bool;

    public function removeCompanyImage(Company $company): void;

    public function findCompany(string $query, ?string $country = null): ?CompanyResource;

    public function addCompanyToWorkspace(Company $company): void;

    public function getCompanies(int $perPage = 15): LengthAwarePaginator;
}
