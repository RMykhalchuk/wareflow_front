<?php

namespace App\Http\Requests\Web\Task;

use App\Enums\Task\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'processing_type' => ['required', 'string', 'max:50'],
            'type_id' => ['required', 'integer', 'exists:task_types,id'],
            'kind' => ['required', 'string', 'max:50'],
            'executors' => ['nullable', 'array'],
            'executors.*' => ['integer', 'exists:users,id'],
            'document_id' => ['nullable', 'uuid'],
            'priority' => ['required', 'integer', 'min:1'],
            'comment' => ['nullable', 'string'],
            'task_data' => ['nullable', 'array']
        ];
    }
}
