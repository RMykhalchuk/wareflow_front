<?php

namespace App\Models\Dictionaries;

use App\Events\RegistersChangedStatus;
use App\Models\User;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

final class Register extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;

    protected $guarded = [];
    protected $casts = [
        'arrive' => 'datetime:H:i',
        'register' => 'date:H:i d.m.Y',
        'entrance' => 'date:H:i d.m.Y',
        'departure' => 'date:H:i d.m.Y',
    ];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<User>
     */
    public function storekeeper(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'storekeeper_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<User>
     */
    public function manager(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<TransportDownload>
     */
    public function download_method(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TransportDownload::class, 'id', 'download_method_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<DownloadZone>
     */
    public function download_zone(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DownloadZone::class, 'id', 'download_zone_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<RegisterStatus>
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(RegisterStatus::class, 'id', 'status_id');
    }

    public function updateWithRelations($data): void
    {
        $relations = ['storekeeper', 'manager', 'download_zone', 'download_method', 'status'];

        foreach ($relations as $relation) {
            $data = $this->renameRelationField($relation, $data);
        }

        $this->update($data);

        broadcast(new RegistersChangedStatus($this->fresh()));
    }

    private function renameRelationField(string $relationName, $data)
    {
        if (array_key_exists($relationName, $data) && $relationName == 'download_zone') {
            $data[$relationName . '_id'] = DownloadZone::where('name', $data[$relationName])?->first()?->id;
            unset($data[$relationName]);
        } elseif (array_key_exists($relationName, $data) && $relationName == 'download_method') {
            $data[$relationName . '_id'] = TransportDownload::where('name', $data[$relationName])?->first()?->id;
            unset($data[$relationName]);
        } elseif (array_key_exists($relationName, $data) && $relationName == 'status') {
            $data[$relationName . '_id'] = RegisterStatus::where('name', $data[$relationName])?->first()?->id;
            unset($data[$relationName]);
        } elseif (array_key_exists($relationName, $data)) {
            $data[$relationName . '_id'] = User::where(
                DB::raw("surname || ' ' || LEFT(name, 1) || '.'"),
                $data[$relationName]
            )?->first()?->id;
            unset($data[$relationName]);
        }

        return $data;
    }
}
