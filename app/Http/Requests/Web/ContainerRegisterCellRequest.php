<?php

namespace App\Http\Requests\Web;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ContainerRegisterCellRequest.
 */
class ContainerRegisterCellRequest extends FormRequest
{
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
            'cell_id' => 'required|exists:cell_allocations,id',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'cell_id'         => __('localization.leftovers.form.cell'),
        ];
    }
}
