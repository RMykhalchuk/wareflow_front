<?php

namespace App\Http\Requests\Web\LeftoverErp;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LeftoverErpBulkUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validate a root-level array of leftover ERP items.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Root payload must be an array of items
            '*' => ['array'],

            '*.warehouse_erp_id' => ['required', 'string', 'exists:warehouses_erp,id_erp'],
            '*.goods_erp_id'     => ['required', 'string', 'exists:goods,erp_id'],
            '*.batch'            => ['nullable', 'string', 'max:50'],
            '*.quantity'         => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
