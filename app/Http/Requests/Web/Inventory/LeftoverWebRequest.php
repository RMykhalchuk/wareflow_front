<?php

namespace App\Http\Requests\Web\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class LeftoverWebRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'goods_id' => 'required|uuid|exists:goods,id',
            'packages_id' => 'required|uuid|exists:packages,id',
            'quantity' => 'required|numeric|min:0',
            'batch' => 'nullable|string',
            'condition' => 'required',
            'expiration_term' => 'nullable|integer',
            'manufacture_date' => 'nullable|date',
            'bb_date' => 'nullable|date',
            'container_registers_id' => 'nullable|uuid|exists:container_registers,id',
        ];
    }
}
