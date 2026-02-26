<?php

namespace App\Http\Requests\Web\Document;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * DocumentRequest.
 */
class DocumentRequest extends FormRequest
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
            'type_id' => 'required|exists:document_types,id',
            'status_id' => 'required',
            'data' => 'required',
            'warehouse_id' => 'required|exists:warehouses,id',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'type_id'     => __('localization.document_types_index_title'),
            'status_id'     => __('localization.invoice_invoicing_modal_payment_obligation_status'),
            'data'     => __('localization.documents.view.free_selection_label'),
            'warehouse_id'     => __('localization.documents.fields.arrival.data_gotovnosti'),
        ];
    }
}
