<?php

namespace App\Tables\AdditionalEquipment;

use App\Helpers\PostgreHelper;
use App\Models\Entities\Transport\AdditionalEquipment;
use App\Tables\Table\TableFilter;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class TableFacade
{

    /**
     * Retrieves filtered equipment data with related information.
     *
     * @return Collection
     * @throws Exception
     */
    public static function getFilteredData()
    {
        try {
            $relationFields = [
                'company',
                'model.brand',
                'transport' => fn($q) => $q->with(['model', 'brand']),
            ];

            $query = self::buildEquipmentQuery($relationFields);

            $formatTable = new FormatTableData();
            $tableSort = new TableSort($formatTable);
            $filter = new TableFilter($tableSort, $formatTable);

            return $filter->filter($relationFields, $query);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve filtered equipment data: ' . $e->getMessage());
        }
    }

    /**
     * Builds the base query for additional equipment with necessary joins.
     *
     * @param array $relationFields
     * @return Builder
     */
    private static function buildEquipmentQuery(array $relationFields): Builder
    {
        return AdditionalEquipment::with($relationFields)
            ->leftJoin('_d_additional_equipment_brands', 'additional_equipment.brand_id', '=', '_d_additional_equipment_brands.id')
            ->leftJoin('_d_additional_equipment_models', 'additional_equipment.model_id', '=', '_d_additional_equipment_models.id')
            ->leftJoinSub(
                self::buildTransportSubQuery(),
                'transport_info',
                'additional_equipment.transport_id',
                '=',
                'transport_info.id'
            )
            ->leftJoinSub(
                self::buildCompanySubQuery(),
                'company_with_details',
                'additional_equipment.company_id',
                '=',
                'company_with_details.id'
            )
            ->leftJoinSub(
                self::buildDownloadMethodsSubQuery(),
                'methods',
                'additional_equipment.id',
                '=',
                'methods.new_id'
            )
            ->select('additional_equipment.*');
    }


    private static function buildTransportSubQuery()
    {
        return DB::table('transports')
            ->select('transports.id', '_d_transport_brands.name as brand_name', '_d_transport_models.name as model_name')
            ->leftJoin('_d_transport_brands', 'transports.brand_id', '=', '_d_transport_brands.id')
            ->leftJoin('_d_transport_models', 'transports.model_id', '=', '_d_transport_models.id');
    }


    private static function buildCompanySubQuery()
    {
        $concat = PostgreHelper::dbConcat('physical_companies.first_name','physical_companies.surname');
        return DB::table('companies')
            ->select('companies.id', DB::raw("
                CASE
                    WHEN companies.company_type_id = 1
                    THEN {$concat}
                    ELSE legal_companies.name
                END as name
            "))
            ->leftJoin('physical_companies', 'companies.company_id', '=', 'physical_companies.id')
            ->leftJoin('legal_companies', 'companies.company_id', '=', 'legal_companies.id');
    }


    private static function buildDownloadMethodsSubQuery()
    {
        return DB::table('additional_equipment')
            ->select('additional_equipment.id as new_id', DB::raw("
            CASE
                WHEN additional_equipment.download_methods IS NOT NULL THEN
                    (SELECT STRING_AGG(_d_transport_downloads.name, '|' ORDER BY _d_transport_downloads.name)
                     FROM _d_transport_downloads
                     WHERE _d_transport_downloads.id::text = ANY(
                         SELECT jsonb_array_elements_text(
                             CASE
                                 WHEN jsonb_typeof(additional_equipment.download_methods::jsonb) = 'array'
                                 THEN additional_equipment.download_methods::jsonb
                                 ELSE '[]'::jsonb
                             END
                         )
                     ))
                ELSE NULL
            END as new_download_methods
        "));
    }
}
