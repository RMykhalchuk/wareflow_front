<?php

namespace App\Http\Requests\Web\Warehouse\Row;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * RowRequest.
 */
class RowRequest extends FormRequest
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
            'count' => 'nullable|numeric',
            'floors' => 'required|numeric|min:1|max:50',
            'racks' =>  'required|numeric|min:1|max:50',
            'cell_count' => 'required|numeric|min:1|max:500',
            'cell' => 'nullable|array',
            'cell.height' => 'nullable|numeric',
            'cell.width' => 'nullable|numeric',
            'cell.deep' => 'nullable|numeric',
            'cell.max_weight' => 'nullable|numeric',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'floors'           => __('localization.warehouse.row.floors'),
            'racks'            => __('localization.warehouse.row.racks'),
            'cell_count'       => __('localization.warehouse.row.number_of_cells'),

            'cell'             => __('localization.warehouse.row.cells_in_rack'),
            'cell.height'      => __('localization.warehouse.row.height'),
            'cell.width'       => __('localization.warehouse.row.width'),
            'cell.length'        => __('localization.warehouse.row.width'),
            'cell.max_weight'  => __('localization.warehouse.row.height'),
        ];
    }
}
