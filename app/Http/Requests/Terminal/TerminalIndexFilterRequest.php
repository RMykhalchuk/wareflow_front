<?php

namespace App\Http\Requests\Terminal;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

class TerminalIndexFilterRequest extends FormRequest
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
            'executor'  => ['nullable', 'in:all,me'],
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date'],
            'task_id'   => ['nullable', 'integer'],
        ];
    }
}
