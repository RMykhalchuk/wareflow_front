<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Company\LegalCompanyRequest;
use App\Http\Requests\Web\Company\PhysicalCompanyRequest;
use App\Models\Entities\Company\Company;
use App\Services\Web\Company\CompanyService;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    protected CompanyService $service;

    public function __construct(CompanyService $service)
    {
        $this->service = $service;
    }

    /**
     * Get companies
     * @queryParam page int optional Page number. Example: 2
     */
    #[QueryParameter('page', 'int', required: false, example: 2)]
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $companies = $this->service->getCompanies((int)$perPage);

        return response()->json($companies);
    }

    /**
     * Create new legal company
     *
     */
    #[BodyParameter('country', type: 'integer', example: '1', description: 'country id')]
    #[BodyParameter('city', type: 'integer', example: '1', description: 'city id')]
    #[BodyParameter('street', type: 'integer', example: '1', description: 'street id')]
    #[BodyParameter('building_number', type: 'string', example: '1')]
    #[BodyParameter('gln', type: 'string', example: '1234567890123', description: '13 digits')]
    #[BodyParameter('u_country', type: 'integer', example: '1', description: 'legal country id')]
    #[BodyParameter('u_city', type: 'integer', example: '1', description: 'legal city id')]
    #[BodyParameter('u_street', type: 'integer', example: '1', description: 'legal street id')]
    #[BodyParameter('u_building_number', type: 'string', description: 'legal building number', example: '1')]
    #[BodyParameter('u_gln', type: 'string', example: '1234567890123', description: 'legal gln 13 digits')]
    #[BodyParameter('ipn', type: 'string', example: '1234567890', description: '10 digits')]
    #[BodyParameter('mfo', type: 'string', example: '123456', description: '6 digits')]
    #[BodyParameter('erp_id', type: 'string', example: '1234567890', description: 'company erp id')]
    #[BodyParameter('currency', type: 'string', example: '1', description: '1-UAH,2-USD,3-EUR')]
    #[BodyParameter('has_creator', type: 'boolean', example: 'false', description: 'true - user company, false - counterparty')]
    #[BodyParameter('ust_doc', type: 'file', description: 'file - Свідоцтво реєстрації')]
    #[BodyParameter('registration_doc', type: 'file', description: 'file - Установчі документи')]

    public function storeLegal(LegalCompanyRequest $request): JsonResponse
    {
        $company = $this->service->createLegalCompany($request);

        return response()->json($company, 201);
    }

    /**
     * Create new physical company
     *
     */
    #[BodyParameter('country', type: 'integer', example: '1', description: 'country id')]
    #[BodyParameter('city', type: 'integer', example: '1', description: 'city id')]
    #[BodyParameter('street', type: 'integer', example: '1', description: 'street id')]
    #[BodyParameter('building_number', type: 'string', example: '1')]
    #[BodyParameter('ipn', type: 'string', example: '1234567890', description: '10 digits')]
    #[BodyParameter('mfo', type: 'string', example: '123456', description: '6 digits')]
    #[BodyParameter('gln', type: 'string', example: '1234567890123', description: '13 digits')]
    #[BodyParameter('erp_id', type: 'string', example: '1234567890', description: 'company erp id')]
    #[BodyParameter('currency', type: 'string', example: '1', description: '1-UAH,2-USD,3-EUR')]
    #[BodyParameter('has_creator', type: 'boolean', example: 'false', description: 'true - user company, false - counterparty')]


    public function storePhysical(PhysicalCompanyRequest $request): JsonResponse
    {
        $company = $this->service->createPhysicalCompany($request);

        return response()->json($company, 201);
    }

    /**
     * Get company by id
     *
     *  Get one company by UUID
     */
    public function show($id): JsonResponse
    {
        $company = Company::filterByWorkspace()->find($id);
        return response()->json($company);
    }

    /**
     * Update legal company by uuid
     */
    public function updateLegalCompany(LegalCompanyRequest $request, Company $company): Company
    {
        $company->updateLegal($request);
        return $company->fresh();
    }

    /**
     * Update physical company by uuid
     */
    public function updatePhysicalCompany(PhysicalCompanyRequest $request, Company $company): Company
    {
        $company->updatePhysical($request);
        return $company->fresh();
    }
}
