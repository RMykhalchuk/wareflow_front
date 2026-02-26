<?php

namespace App\Http\Requests\Web\SKU;

use Illuminate\Foundation\Http\FormRequest;

final class SKURequest extends FormRequest
{
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
            'name' => 'required|string|max:200',
            'category_id' => 'required',
            'measurement_unit_id' => 'required',
            'weight' => ['required','numeric', 'min:1', 'regex:/^\d+(\.\d{1,2})?$/'],
            'expiration_date' => 'required|numeric|min:0',
            'barcode' => 'required|numeric|digits:11'
        ];
    }
}
