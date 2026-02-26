<?php

namespace App\Http\Requests\Terminal\RevertIncome;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

class ClosePositionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'leftovers' => 'required|array',
            'leftovers.*.batch' => 'required|string',
            'leftovers.*.quantity' => 'required|integer',
            'leftovers.*.package_id' => 'required|uuid|exists:packages,id',
            'leftovers.*.manufacture_date' => 'required|date',
            'leftovers.*.bb_date' => 'required|date',
            'leftovers.*.goods_id' => 'required|uuid|exists:goods,id',
            'leftovers.*.expiration_term' => 'required|integer',
            'leftovers.*.container_id' => 'nullable|uuid|exists:container_registers,id',
            'leftovers.*.has_condition' => 'nullable|boolean',
            'provider_id' => 'required|uuid|exists:companies,id',
            'cell_id' => 'required|uuid|exists:cells,id',
            'document_id' => 'nullable|uuid|exists:documents,id',
        ];
    }
}

