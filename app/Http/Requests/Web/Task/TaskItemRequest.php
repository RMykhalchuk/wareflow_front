<?php

namespace App\Http\Requests\Web\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskItemRequest extends FormRequest
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
            'leftover_id' => ['required', 'uuid'],
            'goods_id' => ['required', 'uuid'],
            'data' => ['nullable', 'array'],
            'packing' => ['required', 'string'],
            'container_id' => ['nullable', 'string'],
            'main_unit_quantity' => ['required', 'numeric', 'min:0'],
            'packing_quantity' => ['required', 'numeric', 'min:0'],
        ];
    }
}
