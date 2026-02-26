<?php

namespace App\Http\Requests\Api\Goods;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    #[BodyParameter(
        'id',
        type: 'integer',
        required: false,
        example: null,
        description: 'Existing package ID for update. If null — new package will be created.'
    )]
    #[BodyParameter(
        'parent_id',
        type: 'integer',
        required: false,
        example: 1,
        description: 'Parent package DB ID. Null if this is a root-level package.'
    )]
    #[BodyParameter('name', type: 'string', example: 'Bottle 1L', description: 'Package name')]
    #[BodyParameter('barcode', type: 'string', example: '0123456789012', description: 'Package barcode')]
    #[BodyParameter(
        'main_units_number',
        type: 'number',
        example: '1.000',
        description: 'Number of main units contained in the package (decimal up to 3 places)'
    )]
    #[BodyParameter(
        'package_count',
        type: 'integer',
        required: false,
        example: '6',
        description: 'Number of sub-packages in this package. Optional.'
    )]
    #[BodyParameter('weight_netto', type: 'number', example: '0.5', description: 'Net weight of package')]
    #[BodyParameter('weight_brutto', type: 'number', example: '0.6', description: 'Gross weight of package')]
    #[BodyParameter('height', type: 'number', example: '10.0')]
    #[BodyParameter('width', type: 'number', example: '5.0')]
    #[BodyParameter('length', type: 'number', example: '20.0')]
    public function rules(): array
    {
        return [
            // Якщо id передано → редагування, якщо null → створення
            'id' => ['nullable', 'integer', 'exists:packings,id'],

            // parent_id повинен існувати тільки якщо він не null
            'parent_id' => [
                'nullable',
                'integer',
                'exists:packings,id',
                // Заборона ставити самим себе у parent
                Rule::notIn([$this->input('id')]),
            ],
            "child_id" => [
                'nullable',
                'integer',
                'exists:packings,id',
                // Заборона ставити самим себе у parent
                Rule::notIn([$this->input('id')]),
            ],

            'name' => ['required', 'string', 'max:50'],
            'barcode' => ['required', 'string', 'max:30'],
            'main_units_number' => ['required', 'numeric', 'min:0.001', 'decimal:0,3'],
            'package_count' => ['nullable', 'integer', 'min:1'],
            'weight_netto' => ['required', 'numeric', 'min:0'],
            'weight_brutto' => ['required', 'numeric', 'min:0'],
            'height' => ['required', 'numeric', 'min:0'],
            'width' => ['required', 'numeric', 'min:0'],
            'length' => ['required', 'numeric', 'min:0'],
            'erp_id' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
