<?php

namespace App\Http\Requests\Web\LeftoverErp;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * LeftoverErpCrudRequest.
 */
class LeftoverErpCrudRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'warehouse_erp_id' => ['required', 'string', 'exists:warehouses_erp,id_erp'],
            'goods_erp_id'     => ['required', 'string', 'exists:goods,erp_id'],
            'batch'            => ['nullable', 'string', 'max:50'],
            'quantity'         => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator): mixed
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'warehouse_erp_id' => __('localization.leftovers.form.warehouses'),
            'goods_erp_id'     => __('localization.leftovers.form.goods'),
            'batch'            => __('localization.leftovers.form.batch'),
            'quantity'         => __('localization.leftovers.form.quantity'),
        ];
    }
}
