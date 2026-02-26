<?php

namespace App\Http\Requests\Web\User;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdatePrivateDataRequest.
 */
final class UpdatePrivateDataRequest extends FormRequest
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
            'email' => 'required|email',
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'patronymic' => 'required|string|max:50',
            'birthday' => 'required|date',
            'phone' => 'required|string|size:13'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'email'                    => __('localization.user_label_email'),
            'name'                     => __('localization.user_first_name'),
            'surname'                  => __('localization.user_last_name'),
            'patronymic'               => __('localization.user_patronymic'),
            'birthday'                 => __('localization.user_birthday_create'),
            'phone'                    => __('localization.user_phone_create'),
        ];
    }
}
