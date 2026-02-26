<?php

namespace App\Http\Requests\Web\Inventory;

use Illuminate\Foundation\Http\FormRequest;

/**
 * LeftoverUpdateRequest.
 */
class LeftoverUpdateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('condition')) {
            $this->merge([
                'condition' => filter_var($this->input('condition'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'goods_id'               => ['sometimes','uuid','exists:goods,id'],
            'package_id'             => ['sometimes','uuid','exists:packages,id'],
            'quantity'               => ['sometimes','integer','min:1'],
            'batch'                  => ['sometimes','string'],
            'manufacture_date'       => ['sometimes','date'],
            'bb_date'                => ['sometimes','date'],
            'expiration_term'        => ['sometimes','integer'],
            'container_registers_id' => ['sometimes','nullable','uuid','exists:container_registers,id'],
            'current_leftovers'      => ['sometimes','nullable','integer','min:0'],
            'leftover_id'            => ['sometimes','nullable','uuid','exists:leftovers,id'],
            'condition' => ['sometimes', 'nullable', 'boolean'],
        ];
    }
}

