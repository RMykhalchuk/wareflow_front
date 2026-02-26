<?php

namespace App\Http\Requests\Web\TransportPlanning;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

final class DestroyTransportPlanningRequest extends FormRequest
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
        $rules['transport-planning'] = [ 'integer', 'exists:transport_planning,id' ];

        return $rules;
    }
}
