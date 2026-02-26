<?php

namespace App\Http\Requests\Web\Document;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

final class DoctypeFieldRequest extends FormRequest
{
    use RequestJson;
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
            'key' => 'required|string|max:200',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:255',
            'type' => 'required',
            'system' => 'required',
            'parameter' => 'nullable|string',
            'model' => 'nullable|string',
        ];
    }
}
