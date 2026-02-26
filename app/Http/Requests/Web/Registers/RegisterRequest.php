<?php

namespace App\Http\Requests\Web\Registers;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

final class RegisterRequest extends FormRequest
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
            'auto_name' => 'required|string',
            'licence_plate' => 'nullable|string',
            'mono_pallet' => 'nullable|numeric',
            'collect_pallet' => 'nullable|numeric',
            'download_type' => 'nullable|string',
            'download_zone' => 'nullable|string',
            'storekeeper' => 'nullable|string',
            'status' => 'nullable|string',
            'manager' => 'nullable|string',
            'time_arrival' => 'required|string',
            'manager_id' => 'nullable|number',
            'storekeeper_id' => 'nullable|number'
        ];
    }
}
