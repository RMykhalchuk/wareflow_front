<?php

namespace App\Http\Requests\Web\Document;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * OutcomeLeftoverRequest.
 */
class OutcomeLeftoverRequest extends FormRequest
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
            'package_id' => ['required', 'uuid', 'exists:packages,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'leftover_id' => ['required', 'uuid', 'exists:leftovers,id'],
            'processing_type' => ['required', 'string', 'max:20']
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'package_id'     => __('localization.document_types_update_fields_dictionary_package_type'),
            'quantity'     => __('localization.document_types_create_document_fields_quantity'),
            'leftover_id'     => __('localization.documents.view.free_selection_label'),
            'processing_type'     => __('localization.leftovers.title'),
        ];
    }
}
