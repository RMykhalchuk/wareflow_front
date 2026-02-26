<?php

namespace App\Http\Requests\Web\TransportPlanning;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

final class AddFailureRequest extends FormRequest
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
            'comment' => [ 'nullable', 'string' ],
            'cause_failure' => [ 'nullable', 'string' ],
            'culprit_of_failure' => [ 'nullable', 'string' ],
            'cost_of_fines' => [ 'nullable', 'string' ],
            'type_id' => [ 'required', 'exists:transport_planning_failure_types,id' ],
            'status' => [ 'integer', 'exists:transport_planning_to_statuses,id' ]
        ];
    }
}
