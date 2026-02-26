<?php

namespace App\Services\Web\Company;

use Illuminate\Support\Facades\DB;

final class CompanyFilter
{
    /**
     * @param (array|string)[]|string $query
     * @param (array|string)[]|string $country
     *
     * @psalm-param array<int|string, array<int|string, mixed>|string>|string $query
     * @psalm-param array<int|string, array<int|string, mixed>|string>|string $country
     *
     * @psalm-return \Illuminate\Support\Collection<int, \stdClass>|null
     */
    public function find(string|array $query, string|array $country): \Illuminate\Support\Collection|null
    {
        $company = $this->getCompanyQuery($query, $country)
            ->join('legal_companies', 'companies.company_id', '=', 'legal_companies.id')
            ->where('ipn', $query)


            ->select('legal_companies.*', 'companies.*')
            ->get();


        if (!$company->isEmpty()) {
            return $company;
        }


        $company = $this->getCompanyQuery($query, $country)
            ->leftJoin('physical_companies', function ($join) {
                $join->on('companies.company_id', '=', 'physical_companies.id')
                    ->where('companies.company_type_id', '=', 1);
            })->where('ipn', $query)
            ->select('physical_companies.*', 'companies.*')
            ->get();

        if (!$company->isEmpty()) {
            return $company;
        }


        $query = explode(' ', $query);
        if (count($query) == 3) {
            $company = $this->getCompanyQuery($query, $country)
                ->leftJoin('legal_companies', function ($join) use ($query) {
                    $join->on('companies.company_id', '=', 'legal_companies.id')
                        ->where('companies.company_type_id', '=', 2);
                })
                ->leftJoin('physical_companies', function ($join) use ($query) {
                    $join->on('companies.company_id', '=', 'physical_companies.id')
                        ->where('companies.company_type_id', '=', 1);
                })->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('legal_companies.name', 'like', '%' . $query[0])
                        ->orWhere(function ($q) use ($query) {
                            $q->where('physical_companies.surname', 'like', '%' . $query[0])
                                ->where('physical_companies.first_name', 'like', '%' . $query[1])
                                ->where('physical_companies.patronymic', 'like', '%' . $query[2]);
                        });
                })
                ->select('physical_companies.*', 'legal_companies.*', 'companies.*')
                ->get();
        } elseif (count($query) == 2) {
            $company = $this->getCompanyQuery($query, $country)
                ->leftJoin('legal_companies', function ($join) use ($query) {
                    $join->on('companies.company_id', '=', 'legal_companies.id')
                        ->where('companies.company_type_id', '=', 2);
                })
                ->leftJoin('physical_companies', function ($join) use ($query) {
                    $join->on('companies.company_id', '=', 'physical_companies.id')
                        ->where('companies.company_type_id', '=', 1);
                })->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('legal_companies.name', 'like', '%' . $query[0])
                        ->orWhere(function ($q) use ($query) {
                            $q->where('physical_companies.surname', 'like', '%' . $query[0])
                                ->where('physical_companies.first_name', 'like', '%' . $query[1]);
                        });
                })
                ->select('physical_companies.*', 'legal_companies.*', 'companies.*')
                ->get();
        } else {
            $company = $this->getCompanyQuery($query, $country)
                ->leftJoin('legal_companies', function ($join) use ($query) {
                    $join->on('companies.company_id', '=', 'legal_companies.id')
                        ->where('companies.company_type_id', '=', 2);
                })
                ->leftJoin('physical_companies', function ($join) use ($query) {
                    $join->on('companies.company_id', '=', 'physical_companies.id')
                        ->where('companies.company_type_id', '=', 1);
                })
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('legal_companies.name', 'like', '%' . $query[0])
                        ->orWhere('physical_companies.surname', 'like', '%' . $query[0]);
                })
                ->select('physical_companies.*', 'legal_companies.*', 'companies.*')
                ->get();
        }

        if (!$company->isEmpty()) {
            return $company;
        }
        return null;
    }

    /**
     * @param (array|string)[]|string $country
     *
     * @psalm-param array<int|string, array<int|string, mixed>|string>|string $country
     */
    private function getCompanyQuery(string|array $query, array|string $country): \Illuminate\Database\Query\Builder|null
    {
        $companyQuery = null;

        if ($query) {
            if ($country) {
                $companyArray =
                    DB::table('companies')
                        ->leftJoin('address_details', 'companies.address_id', '=', 'address_details.id')
                        ->leftJoin('_d_countries', 'address_details.country_id', '=', 'countries.id')
                        ->leftJoin('legal_companies', function ($join) {
                            $join->on('companies.company_id', '=', 'legal_companies.id')
                                ->where('companies.company_type_id', '=', 2);
                        })
                        ->leftJoin('physical_companies', function ($join) {
                            $join->on('companies.company_id', '=', 'physical_companies.id')
                                ->where('companies.company_type_id', '=', 1);
                        })
                        ->where('countries.name', 'like', '%' . $country . '%')
                        ->pluck('companies.id')
                        ->toArray();

                $companyQuery = DB::table('companies')->whereIn('companies.id', $companyArray);
            } else {
                $companyQuery = DB::table('companies');
            }
        }

        return $companyQuery;
    }
}
