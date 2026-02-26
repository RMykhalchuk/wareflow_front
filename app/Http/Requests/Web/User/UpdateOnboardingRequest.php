<?php

namespace App\Http\Requests\Web\User;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * UpdateOnboardingRequest.
 */
final class UpdateOnboardingRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        if (empty($this->phone) || strlen((string) $this->phone) < 13) {
            $this->merge(['phone' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'email'      => ['nullable', 'email', Rule::unique('users')->ignore(Auth::id())],
            'name'       => 'required|string|max:50',
            'surname'    => 'required|string|max:50',
            'patronymic' => 'required|string|max:50',
            'phone'      => ['nullable', 'string', 'size:13', Rule::unique('users')->ignore(Auth::id())],
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
