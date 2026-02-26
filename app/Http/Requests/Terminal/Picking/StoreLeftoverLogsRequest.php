<?php

namespace App\Http\Requests\Terminal\Picking;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeftoverLogsRequest extends FormRequest
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
            'container_id' => 'nullable|uuid|exists:containers,id',
            'leftover_id' => 'required|uuid|exists:leftovers,id',
            'package_id' => 'required|uuid|exists:packages,id',
            'quantity' => 'required|numeric|min:1',
            'type' => 'required|in:scan,manual',
        ];
    }
}
