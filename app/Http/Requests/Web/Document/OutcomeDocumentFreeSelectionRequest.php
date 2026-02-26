<?php

namespace App\Http\Requests\Web\Document;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * OutcomeDocumentFreeSelectionRequest.
 */
class OutcomeDocumentFreeSelectionRequest extends FormRequest
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
            'free_selection' => 'required|boolean',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'free_selection'     => __('localization.documents.view.free_selection_label'),
        ];
    }
}
