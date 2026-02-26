<?php

namespace App\Http\Requests\Web\Warehouse;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * WarehouseErpRequest.
 */
final class WarehouseErpRequest extends FormRequest
{
    use RequestJson;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'id_erp' => [
                'required',
                'string',
                Rule::unique('warehouses_erp', 'id_erp')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name'   => __('localization.warehouse_erp.form.name'),
            'id_erp' => __('localization.warehouse_erp.form.erp_id'),
        ];
    }
}
