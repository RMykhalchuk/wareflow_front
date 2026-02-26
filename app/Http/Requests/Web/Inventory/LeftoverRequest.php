<?php

namespace App\Http\Requests\Web\Inventory;

use App\Http\Requests\RequestJson;
use App\Models\Entities\Leftover\Leftover;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
class LeftoverRequest extends FormRequest
{
    use RequestJson;

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'leftovers' => 'nullable|array',
            'leftovers.*.id' => 'required|uuid|exists:leftovers,id',
            'leftovers.*.package_id' => 'required|uuid|exists:packages,id',
            'leftovers.*.quantity' => 'required|numeric|min:0',
            'container_registers' => 'nullable|array',
            'container_registers.*.id' => 'required|uuid|exists:container_registers,id',
            'container_registers.*.quantity' => 'required|numeric|min:0',
        ];
    }
}
