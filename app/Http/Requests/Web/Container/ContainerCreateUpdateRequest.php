<?php

namespace App\Http\Requests\Web\Container;


use App\Http\Requests\RequestJson;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


/**
 * ContainerCreateUpdateRequest.
 */
final class ContainerCreateUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                'min:2'
            ],
            'type_id' => [
                'required',
                'integer',
                'exists:_d_container_types,id'
            ],
            'code_format' => [
                'required',
                'string',
                'size:5',
                'alpha_num',
                Rule::unique('containers', 'code_format')
                    ->ignore(optional($this->route('container'))->id)
                    ->where('creator_company_id', app(AuthContextService::class)->getCompanyId()),
            ],
            'weight' => [
                'required',
                'numeric',
                'min:0.01',
                'max:99999.99',
            ],
            'height' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999.99',
            ],
            'length' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999.99',
            ],
            'width' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999.99',
            ],
            'max_weight' => [
                'required',
                'numeric',
                'min:0.01',
                'max:99999.99',
                'gte:weight',
            ],
            'reversible' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name'        => __('localization.container_create_name'),
            'type_id'     => __('localization.container_create_container_type_label'),
            'code_format' => __('localization.container_edit_unique_number'),

            'weight'      => __('localization.container_edit_weight'),
            'height'      => __('localization.container_edit_height'),
            'length'      => __('localization.container_edit_depth'),
            'width'       => __('localization.container_width'),
            'max_weight'  => __('localization.container_create_width_label'),

            'reversible'  => __('localization.container_edit_reversible'),
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        //TODO remove if not needed.
        return [
            'name.required' => 'Назва контейнера є обов\'язковою.',
            'name.unique' => 'Контейнер з такою назвою вже існує.',
            'code_format.regex' => 'Формат коду повинен містити тільки великі літери та цифри.',
            'code_format.unique' => 'Контейнер з таким форматом коду вже існує.',
            'max_weight.gte' => 'Максимальна вага повинна бути більше або дорівнювати власній вазі контейнера.',
            'weight.min' => 'Вага повинна бути більше 0.',
            'height.min' => 'Висота повинна бути більше 0.',
            'length.min' => 'Довжина повинна бути більше 0.',
            'width.min' => 'Ширина повинна бути більше 0.',
        ];
    }
}
