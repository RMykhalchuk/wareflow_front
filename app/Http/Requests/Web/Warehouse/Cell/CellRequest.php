<?php

namespace App\Http\Requests\Web\Warehouse\Cell;

use App\Enums\Containers\Cell\CellType;
use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * CellRequest.
 */
class CellRequest extends FormRequest
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
            'height' => 'required|numeric|min:0.1',
            'width' => 'required|numeric|min:0.1',
            'deep' => 'required|numeric|min:0.1',
            'max_weight' => 'required|numeric|min:0.1'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'height'     => __('localization.warehouse.cells.height'),
            'width'      => __('localization.warehouse.cells.width'),
            'deep'       => __('localization.warehouse.cells.length'),
            'max_weight' => __('localization.warehouse.cells.max_weight'),
        ];
    }
}
