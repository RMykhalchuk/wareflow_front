<?php

namespace App\Http\Requests\Web\Leftover;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

/**
 * Inventory Request.
 */
class LeftoverSyncRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */

    public function rules(): array
    {
        return [
            'leftovers' => ['sometimes', 'array'],
            'leftovers.create' => ['nullable', 'array'],
            'leftovers.update' => ['nullable', 'array'],
            'leftovers.create.*.goods_id' => ['required', 'uuid'],
            'leftovers.create.*.quantity' => ['required', 'integer', 'min:1'],
            'leftovers.create.*.batch' => ['required', 'string'],
            'leftovers.create.*.manufacture_date' => ['required'],
            'leftovers.create.*.bb_date' => ['required'],
            'leftovers.create.*.package_id' => ['required', 'uuid'],
            'leftovers.create.*.warehouse_id' => ['sometimes', 'nullable', 'uuid'],
            'leftovers.create.*.has_condition' => ['nullable', 'boolean'],
            'leftovers.create.*.container_id' => ['nullable', 'uuid'],
            'leftovers.create.*.expiration_term' => ['nullable', 'integer'],
            'leftovers.create.*.cell_id' => ['required', 'uuid'],
            'leftovers.update.*.leftover_id' => ['required', 'uuid'],
            'leftovers.update.*.package_id' => ['required', 'uuid'],
            'leftovers.update.*.quantity' => ['required', 'integer', 'min:0'],
        ];
    }


    /**
     * @param Validator $validator
     * @return mixed
     */
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
