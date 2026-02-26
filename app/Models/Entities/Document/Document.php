<?php

namespace App\Models\Entities\Document;

use App\Http\Requests\Web\Document\DocumentRequest;
use App\Models\Dictionaries\DocumentStatus;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Task\Task;
use App\Models\Entities\TransportPlanning\TransportPlanning;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Services\Web\Auth\AuthContextService;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Document extends Model
{
    use SoftDeletes;
    use HasUuid;
    use CompanySeparation;
    use LogActivity;

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $guarded = [];

    public static function boot()
    {
        static::creating(function ($model) {
            // Пропускаємо, якщо local_id явно задано
            if (!empty($model->local_id)) {
                return;
            }

            $companyId = app(AuthContextService::class)->getCompanyId();
            if ($companyId) {
                $max = static::where('creator_company_id', $companyId)
                    ->where('type_id', $model->type_id)->max('local_id');
                $model->local_id = $max ? $max + 1 : 1;
            } else {
                $model->local_id = 1;
            }

        });
        parent::boot();
    }

    public function documentType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DocumentType::class, 'id', 'type_id');
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DocumentStatus::class, 'id', 'status_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsToMany<self>
     */
    public function relatedDocuments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_relations', 'document_id', 'related_id');
    }

    public function leftovers(): hasMany
    {
        return $this->hasMany(Leftover::class, 'document_id');
    }

    public function warehouse(): HasOne
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function tasks(): hasMany
    {
        return $this->hasMany(Task::class, 'document_id', 'id');
    }

    public function documentLeftoverReservations(): HasMany
    {
        return $this->hasMany(DocumentLeftoverReservation::class, 'document_id', 'id');
    }

    /**
     * @return (array|mixed)[]
     *
     * @psalm-return array{header: array, header_ids: mixed}
     */
    public function allBlocksToArray(): array
    {
        $data = json_decode($this->data, true);
        $headerFields = $data['header'];
        $customBlockFields = [];

        if (isset($data['custom_blocks']) && is_array($data['custom_blocks'])) {
            foreach ($data['custom_blocks'] as $block) {
                foreach ($block as $key => $value) {
                    $customBlockFields[$key] = $value;
                }
            }
        }

        return ['header' => array_merge($headerFields, $customBlockFields),
            'header_ids' => $data['header_ids']];
    }


    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Goods>
     */
    public function goods(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Goods::class, 'sku_by_documents', 'document_id', 'goods_id')->withPivot('count', 'data');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsToMany<TransportPlanning>
     */
    public function transport_plannings(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            TransportPlanning::class,
            'transport_planning_documents',
            'document_id',
            'transport_planing_id'
        )->withPivot('download_start', 'download_end', 'unloading_start', 'unloading_end');
    }

    public static function store(DocumentRequest $request)
    {
        $data = $request->except(['_token']);
        $files = $request->allFiles();

        $document = Document::create(
            [
                'type_id' => $data['type_id'],
                'status_id' => $data['status_id'],
                'data' => $data['data'],
                'warehouse_id' => $data['warehouse_id']
            ]);

        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $filenameWithoutExtension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $file->move(public_path('uploads/documents'), hash('md5', $filenameWithoutExtension . '_' . $document->id) . 'Document' . $extension);
        }

        return $document->id;
    }

    public function updateData(array $request): static
    {
        $this->update([
                          'status_id' => $request['status_id'],
                          'data' => $request['data']
                      ]);

        return $this;
    }

    public function data()
    {
        return json_decode($this->data, true);
    }

    public function products()
    {
        return $this->data()['sku_table'];
    }

    public function getSkuTableInfo(): array
    {
        $data = $this->data();

        if (empty($data['sku_table'])) {
            return [];
        }

        $skuTable = $data['sku_table'];

        $goodsIds = collect($skuTable)
            ->pluck('id')
            ->unique()
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | 1. Goods + прості relations
        |--------------------------------------------------------------------------
        */
        $goodsList = Goods::query()
            ->select([
                         'id',
                         'name',
                         'provider',
                         'brand',
                         'manufacturer',
                         'measurement_unit_id',
                         'category_id',
                     ])
            ->with([
                       'barcodes',
                       'measurement_unit:id,name',
                       'category:id,name',
                   ])
            ->whereIn('id', $goodsIds)
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | 2. Збираємо ВСІ company_id
        |--------------------------------------------------------------------------
        */
        $companyIds = $goodsList
            ->flatMap(fn($g)
                => [
                $g->provider,
                $g->brand,
                $g->manufacturer,
            ])
            ->filter()
            ->unique()
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | 3. Тягнемо назви компаній ОДНИМ SQL
        |--------------------------------------------------------------------------
        */
        $companies = \DB::table('companies')
            ->leftJoin('physical_companies', function ($join) {
                $join->on('companies.company_id', '=', 'physical_companies.id')
                    ->where('companies.company_type_id', 1);
            })
            ->leftJoin('legal_companies', function ($join) {
                $join->on('companies.company_id', '=', 'legal_companies.id')
                    ->where('companies.company_type_id', '!=', 1);
            })
            ->whereIn('companies.id', $companyIds)
            ->select([
                         'companies.id',
                         'companies.company_type_id',
                         \DB::raw("
                CASE
                    WHEN companies.company_type_id = 1 THEN
                        CONCAT(
                            physical_companies.surname, ' ',
                            LEFT(physical_companies.first_name, 1), '.',
                            LEFT(physical_companies.patronymic, 1)
                        )
                    ELSE legal_companies.name
                END AS full_name
            "),
                     ])
            ->pluck('full_name', 'id');

        /*
        |--------------------------------------------------------------------------
        | 4. Збираємо SKU
        |--------------------------------------------------------------------------
        */
        foreach ($skuTable as &$row) {
            $goods = $goodsList[$row['id']] ?? null;

            if (!$goods) {
                $row += [
                    'name' => 'Unknown',
                    'barcode' => '-',
                    'brand' => '-',
                    'manufacturer' => '-',
                    'unit' => '-',
                    'category' => '-',
                    'provider' => '-',
                ];
                continue;
            }

            $row['name'] = $goods->name;
            $row['barcode'] = $goods->barcodes->first()->barcode ?? ' - ';
            $row['brand'] = $companies[$goods->brand] ?? ' - ';
            $row['manufacturer'] = $companies[$goods->manufacturer] ?? ' - ';
            $row['provider'] = $companies[$goods->provider] ?? ' - ';
            $row['unit'] = $goods->measurement_unit->name ?? ' - ';
            $row['category'] = $goods->category->name ?? ' - ';

        }

        return $skuTable;
    }


}
