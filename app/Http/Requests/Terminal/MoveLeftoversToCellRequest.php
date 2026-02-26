<?php

namespace App\Http\Requests\Terminal;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

class MoveLeftoversToCellRequest extends FormRequest
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
            'leftovers' => ['required', 'array'],
            'leftovers.*.id' => ['required', 'string', 'exists:leftovers,id'],
            'leftovers.*.package_id' => ['required', 'string', 'exists:leftovers,id'],
            'leftovers.*.package_quantity' => ['required', 'integer'],
            'containers' => ['required', 'array'],
            'containers.*.id' => ['required', 'string', 'exists:containers,id'],
        ];
    }
}
