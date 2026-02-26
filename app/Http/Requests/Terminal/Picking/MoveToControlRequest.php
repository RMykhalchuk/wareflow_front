<?php

namespace App\Http\Requests\Terminal\Picking;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

class MoveToControlRequest extends FormRequest
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
            'goods_id' => 'required|uuid|exists:goods,id',
            'cell_id' => 'required|uuid|exists:cells,id',
            'container_id' => 'nullable|uuid|exists:container_registers,id',
        ];
    }
}
