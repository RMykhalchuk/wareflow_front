<?php

namespace App\Http\Requests\Web\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

final class AvatarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'avatar' => [
                'required',
                File::types(['png', 'jpeg', 'jpg','gif'])
                    ->max(800)
            ],
        ];
    }
}
