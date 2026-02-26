<?php

namespace App\Http\Resources\Web;


use App\Models\Entities\WarehouseComponents\Warehouse;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    private string $guard;

    public function __construct($resource, $guard)
    {
        $this->guard = $guard;
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[\Override]
    public function toArray($request): array
    {
        $wd = $this->workingDataByGuard;

        //TODO refact because workingDataByGuard() still not correctly apply current company
        $wh = $wd ? $wd->currentWarehouseApp()
                ->withoutGlobalScopes()
                ->first()
            : null;

        $warehouses = $wd
            ? $wd->warehouses()
                ->withoutGlobalScopes()
                ->pluck('name', 'id')
                ->toArray()
            : [];

        $currentWarehouseId = $wd?->current_warehouse_app_id;

        $currentWarehouseName = $wh?->name
            ?? ($currentWarehouseId
                ? Warehouse::withoutGlobalScopes()
                    ->whereKey($currentWarehouseId)
                    ->value('name')
                : null);

        return [
            'id'          => $this->id,
            'full_name'   => $this->surname . ' ' . mb_strtoupper(mb_substr($this->name, 0, 1)) . '.',
            'phone'       => $this->phone,
            'email'       => $this->email,
            'birthday'    => $this->birthday,

            'position_name' => $wd?->position?->name,
            'position_key'  => $wd?->position?->key,

            'working_data' => [
                'workspace_id'           => $wd?->workspace_id,
                'creator_company_id'     => $wd?->creator_company_id,

                'current_warehouse_id'   => $currentWarehouseId,
                'current_warehouse_name' => $currentWarehouseName,
                'current_warehouse'      => $wh ? [
                    'id'   => $wh->id,
                    'code' => $wh->code ?? null,
                    'name' => $wh->name ?? null,
                ] : null,

                'warehouses'             => $warehouses,
            ],
        ];
    }
}
