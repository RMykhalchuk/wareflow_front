<?php

namespace App\Http\Requests\Web\TransportPlanning;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateStatusRequest extends FormRequest
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
            'address_id' => [ 'required', 'exists:address_details,id' ],
            'status_id' => [ 'required', 'exists:transport_planning_statuses,id' ],
            'date' => [ 'required', 'date' ],
            'comment' => [ 'nullable', 'string' ],
            'status' => [ 'integer', 'exists:transport_planning_to_statuses,id' ]
        ];
    }
}
