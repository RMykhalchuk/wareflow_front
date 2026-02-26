<?php

namespace App\Http\Requests\Web\Auth;

use App\Rules\NotAllSameCharacters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => 'required',
            'login' => 'required',
            'password' => ['required', 'confirmed', 'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                new NotAllSameCharacters(),
            ],
        ];
    }
}
