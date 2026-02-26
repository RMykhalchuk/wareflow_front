<?php

namespace App\Http\Requests\Web\Contract;

use App\Http\Requests\RequestJson;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

final class ContractCreateRequest extends FormRequest
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
        $rules = [
            'type_id' => 'required',
            'role' => 'required',
            'company_id' => 'required|exists:companies,id',
            'counterparty_id' => 'required|exists:companies,id',
            'expired_at' => 'nullable',
            'signed_at' => 'nullable',
            'file' => 'nullable',
            'company_regulation_id' => 'nullable',
            'consideration_send' => 'nullable',
            'regulation_data' => 'nullable'
        ];

        return $rules;
    }

    /**
     * @return never
     */
    #[\Override]
    /**
     * @return never
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json([
            'errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
