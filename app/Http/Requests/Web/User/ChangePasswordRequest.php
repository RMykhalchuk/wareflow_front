<?php

namespace App\Http\Requests\Web\User;

use App\Rules\NotAllSameCharacters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * ChangePasswordRequest.
 */
final class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                new NotAllSameCharacters(),
            ],
            'password_confirmation' => 'required|same:password'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'password'                 => __('localization.user_password'),
            'password_confirmation'         => __('localization.user_edit_confirm_new_password'),
        ];
    }
}
