<?php

namespace App\Factories;

use App\Helpers\PostgreHelper;
use App\Models\Entities\Categories;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Document\Document;
use App\Models\Entities\System\Workspace;
use App\Models\Entities\Transport\AdditionalEquipment;
use App\Models\Entities\Transport\Transport;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\User;
use App\Services\Web\Company\CompanyContextService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntityFactory
{

    public function __construct()
    {
        CompanyContextService::apply();
    }

    public static function company()
    {
        return (new Company())->filterByCreatorCompany()->select(['companies.id'])->addName();
    }

    public static function category()
    {
        return Categories::query()
            ->where('workspace_id', Workspace::current())
            ->select(['id', 'name'])
            ->orderBy('name');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Builder<Warehouse>
     */
    public static function warehouse(): \Illuminate\Database\Eloquent\Builder
    {
        return (new Warehouse())->where('workspace_id', Workspace::current())->select(['id', 'name']);
    }

    public static function transport()
    {
        return (new Transport())->where('workspace_id', Workspace::current())
            ->select(['transports.id'])
            ->addFullName();
    }

    public static function additional_equipment()
    {
        return (new AdditionalEquipment())->where('workspace_id', Workspace::current())
            ->select(['additional_equipment.id'])
            ->addFullName();
    }

    public static function users()
    {
        return (new User())->sameCompany()->select(DB::raw(PostgreHelper::dbConcat('name', 'surname').' AS name'), 'users.id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Builder<Document>
     */
    public static function document_order(): \Illuminate\Database\Eloquent\Builder
    {
        return (new Document())->whereHas('documentType', function ($q) {
            $q->where('key', 'zamovlennia');
        })->select('id', DB::raw(PostgreHelper::dbConcat('№ ', 'documents.id')));
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Builder<Document>
     */
    public static function document_goods_invoice(): \Illuminate\Database\Eloquent\Builder
    {
        return (new Document())->whereHas('documentType', function ($q) {
            $q->where('key', 'tovarna_nakladna');
        })->where('workspace_id', Workspace::current())
            ->select('id', DB::raw(PostgreHelper::dbConcat('№ ', 'documents.id')." as name"));
    }

    public static function driver()
    {
        return User::withWorkingData()
            ->sameCompany()
            ->whereHas('workingData.position', function ($query) {
                $query->where('key', 'driver');
            })
            ->with(['workingData.position'])
            ->select([
                         'users.id',
                         'users.name',
                         'users.surname',
                         DB::raw("users.name || ' ' || users.surname AS full_name")
                     ]);
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Builder<Document>
     */
    public static function document_tn(): \Illuminate\Database\Eloquent\Builder
    {
        return (new Document())->whereHas('documentType', function ($q) {
            $q->where('key', 'tovarna_nakladna');
        })->where('workspace_id', Workspace::current())
            ->select('id', DB::raw(PostgreHelper::dbConcat('№', 'documents.id').'  name'));
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Builder<Document>
     */
    public static function document_transport_request(): \Illuminate\Database\Eloquent\Builder
    {
        return (new Document())->whereHas('documentType', function ($q) {
            $q->where('key', 'zapyt_na_transport');
        })->where('workspace_id', Workspace::current())
            ->select('id', DB::raw('documents.id::text as name'));
    }
}
