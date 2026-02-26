<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Company\LegalCompanyRequest;
use App\Http\Requests\Web\Company\PhysicalCompanyRequest;
use App\Models\Entities\Company\Company;
use App\Services\Web\Company\CompanyServiceInterface;
use App\Tables\Company\TableFacade;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * CompanyController.
 */
final class CompanyController extends Controller
{
    /**
     * @param CompanyServiceInterface $companyService
     */
    public function __construct(
        private CompanyServiceInterface $companyService
    ) {
        $this->middleware('can:view-dictionaries')->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
            'show',
            'filter',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $data = $this->companyService->getAllCompanies();

        return view('company.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = $this->companyService->getCreateFormData();

        return view('company.create', $data);
    }

    /**
     * @param LegalCompanyRequest $request
     * @return JsonResponse
     */
    public function storeLegalCompany(LegalCompanyRequest $request): JsonResponse
    {
        $company = $this->companyService->createLegalCompany($request);

        return response()->json($company->toArray());
    }

    /**
     * @param PhysicalCompanyRequest $request
     * @return JsonResponse
     */
    public function storePhysicalCompany(PhysicalCompanyRequest $request): JsonResponse
    {
        $company = $this->companyService->createPhysicalCompany($request);

        return response()->json($company->toArray());
    }

    /**
     * @param Company $company
     * @return View
     */
    public function show(Company $company): View
    {
        $data = $this->companyService->getCompanyShowData($company);

        return view('company.company-info', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company): View
    {
        $data = $this->companyService->getEditFormData($company);

        if ($company->type->key == 'physical') {
            return view('company.edit-physical', $data);
        }

        return view('company.edit-legal', $data);
    }

    /**
     * @param LegalCompanyRequest $request
     * @param Company $company
     * @return RedirectResponse
     */
    public function updateLegalCompany(LegalCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->companyService->updateLegalCompany($request, $company);

        return redirect()->back();
    }

    /**
     * @param PhysicalCompanyRequest $request
     * @param Company $company
     * @return RedirectResponse
     */
    public function updatePhysicalCompany(PhysicalCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->companyService->updatePhysicalCompany($request, $company);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): RedirectResponse
    {
        $this->companyService->deleteCompany($company);

        return redirect()->route('companies.index');
    }

    /**
     * @param Company $company
     * @return Response
     */
    public function removeImage(Company $company): Response
    {
        $this->companyService->removeCompanyImage($company);

        return response('OK');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function find(Request $request): JsonResponse
    {
        $query = $request->get('query');
        $country = $request->get('country');

        try {
            $company = $this->companyService->findCompany($query, $country);

            if ($company) {
                return response()->json($company);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return response()->json(['message' => 'Company not found'], 404);
    }

    /**
     * @param Company $company
     * @param Request $request
     * @return Response
     */
    public function addCompanyToWorkspace(Company $company, Request $request): Response
    {
        $this->companyService->addCompanyToWorkspace($company);

        return response('OK');
    }

    /**
     * @return mixed
     */
    public function filter(): mixed
    {
        return TableFacade::getFilteredData();
    }
}
