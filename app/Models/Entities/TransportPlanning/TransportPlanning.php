<?php

namespace App\Models\Entities\TransportPlanning;

use App\Events\RegistersChangedStatus;
use App\Models\Dictionaries\Register;
use App\Models\Dictionaries\TransportPlanningStatus;
use App\Models\Dictionaries\TransportPlanningToStatus;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Transport\AdditionalEquipment;
use App\Models\Entities\Transport\Transport;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\User;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use App\Traits\TransportPlanningDataTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class TransportPlanning extends Model
{
    use TransportPlanningDataTrait;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    //field names in goods invoices document


    public static function getFieldsByType(string $type): array
    {
        $array = [];
        if ($type == 'zapyt_na_transport') {
            $array['companyProviderField'] = '1select_field_1'; //Оператор
            $array['companyCustomerField'] = '2select_field_2'; // Замовник
            $array['loadingWarehouseField'] = '7select_field_7';
            $array['unloadingWarehouseField'] = '8select_field_8';
            $array['loadingDate'] = '24dateTimeRange_field_24';
            $array['unloadingDate'] = '21dateTimeRange_field_21';
            $array['pallet'] = '14text_field_14';
            $array['weight'] = '16text_field_16';
        } else {
            $array['companyProviderField'] = '1select_field_1';
            $array['companyCustomerField'] = '2select_field_2';
            $array['loadingWarehouseField'] = '3select_field_3';
            $array['unloadingWarehouseField'] = '4select_field_4';
            $array['loadingDate'] = '5dateTimeRange_field_5';
            $array['unloadingDate'] = '6dateTimeRange_field_6';
            $array['comment'] = '10comment_field_10';
        }

        return $array;
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Document>
     */
    public function documents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,
            'transport_planning_documents',
            'transport_planing_id',
            'document_id'
        )->withPivot('download_start', 'download_end', 'unloading_start', 'unloading_end');
    }



    public function statuses()
    {
        return $this->belongsToMany(
            TransportPlanningStatus::class,
            'transport_planning_to_statuses',
            'transport_planning_id',
            'status_id'
        )
            ->withPivot(
                'id',
                'date',
                'comment',
                'address_id',
                'address_details.full_address as address',
                'transport_planning_failures.id as failure_id',
                'transport_planning_failures.type_id as failure_type_id',
                'transport_planning_failures.type_name as failure_type',
                'transport_planning_failures.cause_failure as cause_failure',
                'transport_planning_failures.culprit_of_failure as culprit_of_failure',
                'transport_planning_failures.cost_of_fines as cost_of_fines',
                'transport_planning_failures.comment as failure_comment',
            )
            ->joinAddress('transport_planning_to_statuses.address_id')
            ->leftJoin(DB::raw('(SELECT transport_planning_failures.*, transport_planning_failure_types.name as type_name FROM transport_planning_failures LEFT JOIN transport_planning_failure_types ON transport_planning_failures.type_id = transport_planning_failure_types.id) AS transport_planning_failures'), 'transport_planning_to_statuses.id', '=', 'transport_planning_failures.status_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function carrier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_carrier_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function payer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'payer_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_provider_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Transport>
     */
    public function transport(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Transport::class, 'transport_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<AdditionalEquipment>
     */
    public function additional_equipment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdditionalEquipment::class, 'additional_equipment_id');
    }

    public static function store(\Illuminate\Http\Request $request)
    {
        $data = $request->except(['_token', 'documents']);

        $data['creator_id'] = $request->user()->id;

        $transport_planing = TransportPlanning::create($data);

        TransportPlanningToStatus::create([
            'status_id' => 1,
            'transport_planning_id' => $transport_planing->id,
        ]);

        if ($request->get('documents')) {
            $documents = $request->get('documents');
            $docsArr = json_decode($documents, true);

            $docsIds = array_map(function ($item) {
                return $item['id'];
            }, $docsArr);
            $docs = Document::whereIn('id', $docsIds)->get();
            $documentKey = $docs[0]->documentType->key;

            $fieldArray = TransportPlanning::getFieldsByType($documentKey);
            //add relations to documents
            foreach ($docsArr as $document) {
                $transport_planing->documents()->attach($document['id'], [
                    'download_start' => $document['download_start'],
                    'download_end' => $document['download_end'],
                    'unloading_start' => $document['unloading_start'],
                    'unloading_end' => $document['unloading_end']
                ]);

                if ($request->get('init_transport')) {
                    $docData = json_decode($docs->firstWhere('id', $document['id'])->data);
                    $transport = Transport::where('transports.id', $data['transport_id'])->select(['transports.id', 'transports.license_plate'])->addFullName()->first();
                    $additionalEquipment = AdditionalEquipment::where('id', $data['additional_equipment_id'])->first();

                    $downloadMethod = json_decode($additionalEquipment->download_methods, true)[0];

                    $loadRegister = Register::create([
                        'time_arrival' => $document['download_start'],
                        'auto_name' => $transport->name,
                        'warehouse_id' => $docData->header_ids->{$fieldArray['loadingWarehouseField'] . '_id'},
                        'download_method_id' => $downloadMethod,
                        'licence_plate' => $transport->license_plate,
                        'status_id' => 1
                    ]);

                    broadcast(new RegistersChangedStatus($loadRegister->fresh()));

                    $unloadRegister = Register::create([
                        'time_arrival' => $document['unloading_start'],
                        'auto_name' => $transport->name,
                        'warehouse_id' => $docData->header_ids->{$fieldArray['unloadingWarehouseField'] . '_id'},
                        'download_method_id' => $downloadMethod,
                        'licence_plate' => $transport->license_plate,
                        'status_id' => 1
                    ]);

                    broadcast(new RegistersChangedStatus($unloadRegister->fresh()));
                }
            }
        }

        return $transport_planing->id;
    }

    /**
     * @psalm-param 0 $allPallet
     */
    private function getTransportPlanningData(int &$allPallet, array $fieldArray, string $documentKey, $item)
    {


        $item->company_provider = $item->data->header->{$fieldArray['companyProviderField']};
        $item->company_customer = $item->data->header->{$fieldArray['companyCustomerField']};


        $loadWarehouse = Warehouse::with('address')->joinAddress('warehouses.address_id', false)
            ->find($item->data->header_ids->{$fieldArray['loadingWarehouseField'] . '_id'});

        $unloadWarehouse = Warehouse::with('address')->joinAddress('warehouses.address_id', false)
            ->find($item->data->header_ids->{$fieldArray['unloadingWarehouseField'] . '_id'});

        $item->loading_warehouse = !$loadWarehouse->full_address ? $loadWarehouse->address->comment : $loadWarehouse->full_address;

        $item->unloading_warehouse = !$unloadWarehouse->full_address ? $unloadWarehouse->address->comment : $unloadWarehouse->full_address;

        if ($documentKey == 'zapyt_na_transport') {
            $item->loading_date = [
                'date' => $item->data->custom_blocks->{3}->{$fieldArray['loadingDate']}[0],
                'from' => $item->data->custom_blocks->{3}->{$fieldArray['loadingDate']}[1],
                'to' => $item->data->custom_blocks->{3}->{$fieldArray['loadingDate']}[2]
            ];
            $item->unloading_date = [
                'date' => $item->data->custom_blocks->{2}->{$fieldArray['unloadingDate']}[0],
                'from' => $item->data->custom_blocks->{2}->{$fieldArray['unloadingDate']}[1],
                'to' => $item->data->custom_blocks->{2}->{$fieldArray['unloadingDate']}[2]
            ];
        } else {
            $item->loading_date = [
                'date' => $item->data->header->{$fieldArray['loadingDate']}[0],
                'from' => $item->data->header->{$fieldArray['loadingDate']}[1],
                'to' => $item->data->header->{$fieldArray['loadingDate']}[2]
            ];
            $item->unloading_date = [
                'date' => $item->data->header->{$fieldArray['unloadingDate']}[0],
                'from' => $item->data->header->{$fieldArray['unloadingDate']}[1],
                'to' => $item->data->header->{$fieldArray['unloadingDate']}[2]
            ];
        }


        $weight = 0;
        $pallet = 0;

        //       TODO fix this code. Error undefined weight_brutto or null

        //        if ($item->goods->count()) {
        //            $item->goods->each(function ($item) use (&$weight, &$pallet) {
        //                $weight += $item->weight_brutto * $item->pivot->count + $item->packages->first()->weight_brutto;
        //                $pallet += intval(ceil($item->pivot->count / $item->packages->first()->number));
        //            });
        //        }

        if ($item->goods->count()) {
            $item->goods->each(function ($item) use (&$weight, &$pallet) {
                $weight += $item->pivot->count;
                $pallet += intval(ceil($item->pivot->count));
            });
        }

        $item->weight = $weight;
        $item->pallet = $pallet;
        $allPallet += $pallet;

        return $item;
    }

    public function getItem($id)
    {

        $transportPlanning = TransportPlanning::relations()
            ->where('id', $id)
            ->first();

        $documentKey = $transportPlanning->documents[0]->documentType->key;

        $fieldArray = TransportPlanning::getFieldsByType($documentKey);

        $allPallet = 0;

        $transportPlanning->documents = $transportPlanning->documents->map(function ($item) use (&$allPallet, $fieldArray, $documentKey) {
            $item->data = json_decode($item->data);
            return $this->getTransportPlanningData($allPallet, $fieldArray, $documentKey, $item);
        });

        $transportPlanning->allPallets = $allPallet;

        return $transportPlanning;
    }

    public function getByDate($date)
    {
        $transportPlannings = TransportPlanning::relationsByDate($date)
            ->filterByDocumentsDate($date)
            ->get();


        $transportPlannings = $transportPlannings->map(function ($item) {
            $allPallet = 0;
            $documentKey = $item->documents[0]->documentType->key;

            $fieldArray = TransportPlanning::getFieldsByType($documentKey);

            $item->documents = $item->documents->map(function ($item) use (&$allPallet, $fieldArray, $documentKey) {
                $item->data = json_decode($item->data);
                return $this->getTransportPlanningData($allPallet, $fieldArray, $documentKey, $item);
            });

            $item->countPallets = $allPallet;

            return $item;
        });

        return $transportPlannings;
    }
}
