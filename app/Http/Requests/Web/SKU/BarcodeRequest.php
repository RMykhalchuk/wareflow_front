<?php

namespace App\Http\Requests\Web\SKU;

use Illuminate\Foundation\Http\FormRequest;

final class BarcodeRequest extends FormRequest
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
            'sku_id' => 'required',
            'barcode' => 'required|numeric|digits:11'
        ];
    }
}
