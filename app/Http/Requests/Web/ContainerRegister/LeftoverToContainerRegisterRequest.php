<?php

namespace App\Http\Requests\Web\ContainerRegister;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

class LeftoverToContainerRegisterRequest extends FormRequest
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
            'container_register_id' => ['required', 'uuid', 'exists:container_registers,id'],
            'leftover_id_array'     => ['required', 'array', 'min:1'],
            'leftover_id_array.*'   => ['required', 'uuid', 'exists:leftovers,id'],
        ];
    }
}
