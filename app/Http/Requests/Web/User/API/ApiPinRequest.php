<?php

namespace App\Http\Requests\Web\User\API;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

final class ApiPinRequest extends FormRequest
{
    use RequestJson;
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
            'short_username' => ['required','unique:users','string','size:4'],
            'short_password' => 'required|integer|digits:4'
        ];
    }
}
