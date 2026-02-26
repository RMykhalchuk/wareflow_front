<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Dictionary\CountryResource;
use App\Services\Web\Company\CompanyDictionaryService;
use App\Services\Web\Dictionary\DictionaryService;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DictionaryController extends Controller
{

    /**
     * Get companies list
     */
    #[QueryParameter('id', 'Find company by uuid', required: false, type: 'uuid', example: 'f47ac10b-58cc-4372-a567-0e02b2c3d479')]
    #[QueryParameter('query', 'Search company by name', required: false, type: 'string', example: 'Apple')]
    #[QueryParameter(
        'type',
        description: 'Filter companies by type',
        required: false,
        type: 'string',
        example: 'producer'
    )]
    public function getCompanyList(): JsonResource
    {
        return JsonResource::make(new CompanyDictionaryService()->getDictionaryList());
    }

    /**
     * Get countries list
     */
    public function getCountry()
    {
        return CountryResource::collection(new DictionaryService()->getDictionaryList('country'));
    }

    /**
     * Get measurement units list
     */
    public function getProductUnits()
    {
        return JsonResource::make(new DictionaryService()->getDictionaryList('measurement_unit'));
    }

    /**
     * Get ADR's list
     */
    public function getAdr()
    {
        return JsonResource::make(new DictionaryService()->getDictionaryList('adr'));
    }

    /**
     * Get package types
     */
    public function getPackageType()
    {
        return JsonResource::make(new DictionaryService()->getDictionaryList('package_type'));
    }

}
