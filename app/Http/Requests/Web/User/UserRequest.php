<?php

namespace App\Http\Requests\Web\User;

use App\Http\Requests\RequestJson;
use App\Rules\NotAllSameCharacters;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\RequiredIf;

/**
 * UserRequest.
 */
class UserRequest extends FormRequest
{
    use RequestJson;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'patronymic' => 'required|string|max:50',
            'birthday' => 'required|date',
            'phone' => 'required|string|size:13',
            'password' => ['required_if:new_user,1', 'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
                new NotAllSameCharacters(),
            ],
            'avatar' => 'nullable|image|max:2048',
            'documents.*' => 'nullable|file|max:10240',
            'position' => 'required|string',
            'role' => 'required',
        //    'company_id' => 'required|uuid',
            'health_book_number' => 'required_if:position,driver|numeric|digits_between:4,20',
            'driver_license_date' => 'required_if:position,driver|date',
            'health_book_date' => 'required_if:position,driver|date',
            'driving_license' => [new RequiredIf($this->position == 'driver' && $this->need_file == 'true')],
            'driving_license_number' => ['required_if:position,driver', 'size:9', 'regex:/[АВСЕНІКМОРТХABCEHIKMOPTX]{3}\d{6}/i'],
            'health_book' => [new RequiredIf($this->position == 'driver' && $this->need_file == 'true')],
            'pin' => 'required',
            'sex'=>'required',
            'warehouse_ids'   => ['nullable', 'array'],
            'warehouse_ids.*' => ['nullable', 'uuid', 'exists:warehouses,id'],
            'current_warehouse_id' => ['sometimes', 'nullable', 'uuid', 'exists:warehouses,id'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'email'                    => __('localization.user.create.email_create'),
            'name'                     => __('localization.user.create.first_name'),
            'surname'                  => __('localization.user.create.last_name'),
            'patronymic'               => __('localization.user.create.patronymic'),
            'birthday'                 => __('localization.user.create.birthday_create'),
            'phone'                    => __('localization.user.create.phone_create'),

            'password'                 => __('localization.user.create.password'),
            'avatar'                   => __('localization.user.create.user_avatar'),
            'documents.*'              => __('localization.user.create.user_documents'),

            'position'                 => __('localization.user.create.company_position'),
            'role'                     => __('localization.user.create.system_role'),
            'company_id'               => __('localization.user.create.company_label'),

            'health_book_number'       => __('localization.user.create.user_health_book_number_label'),
            'driver_license_date'      => __('localization.user.create.user_driver_license_term'),
            'health_book_date'         => __('localization.user.create.user_health_book_term'),

            'driving_license'          => __('localization.user.create.user_driver_license'),
            'driving_license_number'   => __('localization.user.create.user_edit_driver_license_number'),
            'health_book'              => __('localization.user.create.user_health_book'),

            'pin'                      => __('localization.user.create.pin-title'),
            'sex'                      => __('localization.user.create.sex'),

            'warehouse_ids'            => __('localization.user.create.user_warehouses'),
            'warehouse_ids.*'          => __('localization.user.create.user_warehouses'),
            'current_warehouse_id'     => __('localization.user.create.user_current_warehouse'),
        ];
    }
}
