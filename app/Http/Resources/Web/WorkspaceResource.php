<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

final class WorkspaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    #[\Override]
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar_type' => $this->avatar_type,
            'avatar_color' => $this->avatar_color,
            'warehouse' => $this->warehouse,
            'employees' => $this->employees,
            'integration' => $this->integration,
            'employees_count' => $this->employees_count,
            'price' => $this->employees_count * 200,
            'current' => $this->id === Auth::user()->current_workspace_id
        ];
    }
}
