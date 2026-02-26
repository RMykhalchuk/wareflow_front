<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\RequestJson;

/**
 * Auth Check User Post Request.
 */
class AuthCheckUserPostRequest extends FormRequest
{
    use RequestJson;

    /**
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('id') && is_string($this->input('id'))) {
            $this->merge(['id' => trim($this->input('id'))]);
        }
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'uuid', 'exists:users,id'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'id.required' => 'User id is required.',
            'id.uuid'     => 'User id must be a valid UUID.',
            'id.exists'   => 'User not found.',
        ];
    }
}
