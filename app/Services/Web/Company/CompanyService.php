<?php

namespace App\Services\Web\Company;

use App\Http\Requests\Web\Company\LegalCompanyRequest;
use App\Http\Requests\Web\Company\PhysicalCompanyRequest;
use App\Http\Resources\Web\CompanyResource;
use App\Interfaces\StoreFileInterface;
use App\Models\Dictionaries\LegalType;
use App\Models\Entities\Address\Country;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Company\CompanyToWorkspace;
use App\Models\Entities\System\FileLoad;
use App\Models\Entities\System\Workspace;
use Illuminate\Pagination\LengthAwarePaginator;


class CompanyService implements CompanyServiceInterface
{
    public function __construct(
        private CompanyFilter $companyFilter,
        private StoreFileInterface $fileStorage
    ) {}

    public function getAllCompanies(): array
    {
        return [
            'companies' => Company::with('company')->get(),
            'companiesAll' => Company::all()
        ];
    }

    public function getCreateFormData(): array
    {
        return [
            'countries' => Country::all(),
            'legalTypes' => LegalType::all()
        ];
    }

    public function createLegalCompany(LegalCompanyRequest $request): Company
    {
        return Company::createLegal($request);
    }

    public function createPhysicalCompany(PhysicalCompanyRequest $request): Company
    {
        return Company::createPhysical($request);
    }

    public function getCompanyShowData(Company $company): array
    {
        $dataArray = ['company' => $company];

        if ($company->company_type_id == 2) {

            $dataArray['registerFile'] = $this->getCompanyFile(
                $company,
                'company/docs/registration',
                $company->company->reg_doctype ?? ""
            );

            $dataArray['installFile'] = $this->getCompanyFile(
                $company,
                'company/docs/install',
                $company->company->install_doctype ?? ""
            );

        }

        return $dataArray;
    }

    public function getEditFormData(Company $company): array
    {
        $data = [
            'company' => $company,
            'countries' => Country::all()
        ];

        if ($company->type->key !== 'physical') {
            $data['legalTypes'] = LegalType::all();
        }

        return $data;
    }

    public function updateLegalCompany(LegalCompanyRequest $request, Company $company): Company
    {
        $company->updateLegal($request);
        return $company->fresh();
    }

    public function updatePhysicalCompany(PhysicalCompanyRequest $request, Company $company): Company
    {
        $company->updatePhysical($request);
        return $company->fresh();
    }

    public function deleteCompany(Company $company): bool
    {
        return $company->delete();
    }

    public function removeCompanyImage(Company $company): void
    {
        $this->fileStorage->deleteFile('company/image', $company, 'img_type');
    }

    public function findCompany(string $query, ?string $country = null): ?CompanyResource
    {
        if (empty($query)) {
            return null;
        }

        try {
            $company = $this->companyFilter->find($query, $country);

            if ($company && !$company->isEmpty()) {
                return CompanyResource::make($company);
            }
        } catch (\Exception $exception) {
            throw new \Exception('Company not found');
        }

        return null;
    }

    public function addCompanyToWorkspace(Company $company): void
    {
        CompanyToWorkspace::firstOrCreate([
                                              'company_id' => $company->id,
                                              'workspace_id' => Workspace::current()
                                          ]);
    }

    private function getCompanyFile(Company $company, string $path, string $doctype): ?FileLoad
    {
        return FileLoad::where('path', $path)
            ->where('new_name', $company->company->id . '.' . $doctype)
            ->first();
    }

    public function getCompanies(int $perPage = 15): LengthAwarePaginator
    {
        return Company::with('company')->filterByWorkspace()->orderBy('id', 'desc')->paginate($perPage);
    }
}
