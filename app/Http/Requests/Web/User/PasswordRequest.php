<?php

namespace App\Http\Requests\Web\User;

use App\Http\Requests\RequestJson;
use App\Rules\NotAllSameCharacters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * PasswordRequest.
 */
final class PasswordRequest extends FormRequest
{
    use RequestJson;
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
            //'login' => 'required|string|max:50',
            'password' => ['required', 'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                new NotAllSameCharacters(),
            ],
            'new_password' => ['required', 'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                new NotAllSameCharacters(),
            ],
            'confirm_password' => 'required|same:new_password'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'password'                 => __('localization.user_password'),
            'new_password'             => __('localization.user_edit_new_password'),
            'confirm_password'         => __('localization.user_edit_confirm_new_password'),
        ];
    }
}
