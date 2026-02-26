<?php

namespace App\Http\Requests\Web\Document;

use App\Enums\Documents\DocumentKind;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * DocumentTypeRequest.
 */
final class DocumentTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:200',
            'settings' => 'required|string',
            'kind' => ['required', new Enum(DocumentKind::class)]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name'     => __('localization.document_types_create_name_placeholder'),
            'settings' => __('localization.document_types_edit_add_new_fields_modal_body_additional_settings_dateRange_title'),
            'kind'     => __('localization.document_types_update_fields_dictionary_transport_kind'),
        ];
    }
}
