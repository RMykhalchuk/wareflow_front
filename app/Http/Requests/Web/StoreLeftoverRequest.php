<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreLeftoverRequest.
 */
class StoreLeftoverRequest extends FormRequest
{
    use RequestJson;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'goods_id' => 'required|uuid|exists:goods,id',
            'cell_id' => 'required|exists:cells,id',
            'quantity' => 'required|integer|min:1',
            'batch' => 'required|string',
            'manufacture_date' => 'required|date',
            'expiration_term' => 'required|integer',
            'bb_date' => 'required|date',
            'package_id' => 'required|uuid|exists:packages,id',
            'warehouse_id' => 'required|uuid|exists:warehouses,id',
            'has_condition' => 'required|boolean',
            'container_id' => 'nullable|uuid|exists:container_registers,id'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'goods_id'        => __('localization.leftovers.form.goods'),
            'cell_id'         => __('localization.leftovers.form.cell'),
            'quantity'        => __('localization.leftovers.form.quantity'),
            'batch'           => __('localization.leftovers.form.batch'),
            'manufacture_date'=> __('localization.leftovers.form.manufacture_date'),
            'expiration_term' => __('localization.leftovers.form.expiration_term'),
            'bb_date'         => __('localization.leftovers.form.bb_date'),
            'package_id'      => __('localization.leftovers.form.packages'),
            'warehouse_id'    => __('localization.leftovers.form.warehouses'),
            'has_condition'   => __('localization.leftovers.form.condition'),
            'container_id'    => __('localization.leftovers.form.container'),
        ];
    }
}
