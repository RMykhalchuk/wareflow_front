<?php

namespace App\Http\Requests\Web\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateCurrentWarehouseRequest.
 */
class UpdateCurrentWarehouseRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'current_warehouse_id' => ['nullable', 'uuid', 'exists:warehouses,id'],
        ];
    }
}
