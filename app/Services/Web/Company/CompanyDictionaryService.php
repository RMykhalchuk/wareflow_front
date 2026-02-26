<?php

namespace App\Services\Web\Company;

use App\Models\Dictionaries\CompanyCategory;
use App\Models\Entities\Company\Company;
use Illuminate\Support\Facades\DB;

final class CompanyDictionaryService
{
    public function getDictionaryList()
    {
        if (!empty($_GET['id'])) {
            return Company::select('companies.id')
                ->where('companies.id', $_GET['id'])
                ->filterByWorkspace()
                ->addName()
                ->first();
        }

        if (!empty($_GET['query'])) {
            $search = '%' . $_GET['query'] . '%';

            $company = Company::select('companies.id')
                ->leftJoin('physical_companies', 'companies.company_id', '=', 'physical_companies.id')
                ->leftJoin('legal_companies', 'companies.company_id', '=', 'legal_companies.id')
                ->select([
                    'companies.id',
                    'companies.company_type_id',
                ])
                ->addSelect(DB::raw("
                    CASE
                        WHEN companies.company_type_id = 1
                            AND CONCAT(physical_companies.first_name, ' ', physical_companies.surname) ILIKE ?
                        THEN CONCAT(physical_companies.first_name, ' ', physical_companies.surname)

                        WHEN companies.company_type_id = 2
                            AND legal_companies.name ILIKE ?
                        THEN legal_companies.name

                        ELSE ''
                    END AS name
                "))
                ->addBinding([$search, $search], 'select')
                ->where(function ($query) use ($search) {
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('companies.company_type_id', 1)
                            ->whereRaw(
                                "CONCAT(physical_companies.first_name, ' ', physical_companies.surname) ILIKE ?",
                                [$search]
                            );
                    })
                        ->orWhere(function ($subQuery) use ($search) {
                            $subQuery->where('companies.company_type_id', 2)
                                ->where('legal_companies.name', 'ilike', $search);
                        });
                })
                ->filterByWorkspace()
                ->limit(25);

        } else {
            $company = Company::select('companies.id')
                ->filterByWorkspace()
                ->limit(25)
                ->addName();
        }

        if (!empty($_GET['type'])) {
            $category = CompanyCategory::where('key', $_GET['type'])->first(['id']);

            if ($category) {
                $company->where('category_id', $category->id);
            }
        }

        return $company->get();
    }
}
