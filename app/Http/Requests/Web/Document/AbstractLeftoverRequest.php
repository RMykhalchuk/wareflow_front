<?php

namespace App\Http\Requests\Web\Document;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

class AbstractLeftoverRequest extends FormRequest
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
            'batch' => 'required|string',
            'has_condition' => 'required|boolean',
            'manufacture_date' => 'required|date',
            'expiration_term' => 'required|integer',
            'bb_date' => 'required|date',
            'package_id' => 'required|uuid|exists:packages,id',
            'container_id' => 'nullable|uuid|exists:container_registers,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
