<?php

namespace App\Http\Requests\Web\Inventory;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Inventory Request.
 */
class InventoryRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function expectsJson(): bool
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
            'show_leftovers'          => ['nullable','boolean'],
            'restrict_goods_movement' => ['nullable','boolean'],
            'process_cell'            => ['required','integer'],
            'priority'         => ['required', 'integer'],
            'performer_type'   => ['required', 'array'],
            'performer_type.*' => ['uuid', 'exists:users,id'],
            'warehouse'      => ['required','uuid'],
            'warehouse_erp'  => ['nullable','uuid'],
            'zone'           => ['nullable','uuid'],
            'sector'         => ['nullable','uuid'],
            'row'            => ['nullable','uuid'],
            'cell'           => ['nullable','uuid'],
            'brand'          => ['nullable','uuid'],

            'category_subcategory' => ['nullable','uuid'],
            'manufacturer'         => ['nullable','uuid'],
            'supplier'             => ['nullable','uuid'],
            'nomenclature'         => ['nullable', 'array'],

            'start_date' => ['nullable','date'],
            'end_date'   => ['nullable','date','after_or_equal:start_date'],
            'comment'    => ['nullable','string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'show_leftovers'          => __('localization.inventory.create.show_leftovers_on_terminal'),
            'restrict_goods_movement' => __('localization.inventory.create.restrict_goods_movement'),
            'process_cell'            => __('localization.inventory.create.process_cell'),
            'priority'                => __('localization.inventory.create.priority'),

            'performer_type'          => __('localization.inventory.create.performer'),
            'performer_type.*'        => __('localization.inventory.create.performer'),

            'warehouse'               => __('localization.inventory.create.warehouse'),
            'warehouse_erp'           => __('localization.inventory.create.warehouse_erp'),
            'zone'                    => __('localization.inventory.create.zone'),
            'sector'                  => __('localization.inventory.create.sector'),
            'row'                     => __('localization.inventory.create.row'),
            'cell'                    => __('localization.inventory.create.cell'),
            'brand'                   => __('localization.inventory.create.brand'),

            'category_subcategory'    => __('localization.inventory.create.category_subcategory'),
            'manufacturer'            => __('localization.inventory.create.manufacturer'),
            'supplier'                => __('localization.inventory.create.supplier'),
            'nomenclature'            => __('localization.inventory.create.nomenclature'),

            'start_date'              => __('localization.inventory.create.start_date'),
            'end_date'                => __('localization.inventory.create.end_date'),
            'comment'                 => __('localization.inventory.create.comment'),
        ];
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator): mixed
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
