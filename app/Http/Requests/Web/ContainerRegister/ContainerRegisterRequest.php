<?php

namespace App\Http\Requests\Web\ContainerRegister;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ContainerRegisterRequest.
 */
class ContainerRegisterRequest extends FormRequest
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
            'code' => ['nullable', 'string', 'max:255'],
            'container_id' => ['required', 'uuid', 'exists:containers,id'],
            'count' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'container_id'        => __('localization.container-register.index.type_label'),
            'count'               => __('localization.container-register.index.count_label'),
            'code'                => __('localization.container_edit_unique_number'),
        ];
    }
}
