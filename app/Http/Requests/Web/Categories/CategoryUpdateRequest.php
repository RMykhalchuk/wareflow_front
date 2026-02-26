<?php

namespace App\Http\Requests\Web\Categories;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
{
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
        $category = $this->route('category');

        return [
            'name'              => ['sometimes','string','max:255',
                Rule::unique('categories','name')
                    ->ignore($category?->id, 'id')
                    ->where(fn($q) => $q->where('workspace_id', $this->input('workspace_id', $category?->workspace_id)))
            ],
            /** @ignoreParam */
            'active'            => ['sometimes','boolean'],
            'parent_id'         => ['nullable','uuid',
                Rule::notIn([$category?->id]),
                Rule::exists('categories','id')->where(fn($q) =>
                $q->where('workspace_id', $this->input('workspace_id', $category?->workspace_id))
                )
            ],
            'goods_category_id' => ['nullable','integer', Rule::exists('_d_goods_categories','id')],
            /** @ignoreParam */
            'workspace_id'      => ['sometimes','integer', Rule::exists('workspaces','id')],
        ];
    }
}
