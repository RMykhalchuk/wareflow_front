<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\RequestJson;

/**
 * Auth Check Pin Post Request.
 */
class AuthCheckPinPostRequest extends FormRequest
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
        if ($this->has('pin') && is_string($this->input('pin'))) {
            $this->merge(['pin' => trim($this->input('pin'))]);
        }
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'id'  => ['required', 'uuid', 'exists:users,id'],
            'pin' => ['required', 'digits:4'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'id.required'  => 'User id is required.',
            'id.uuid'      => 'User id must be a valid UUID.',
            'id.exists'    => 'User not found.',
            'pin.required' => 'PIN is required.',
            'pin.digits'   => 'PIN must be exactly 4 digits.',
        ];
    }
}
