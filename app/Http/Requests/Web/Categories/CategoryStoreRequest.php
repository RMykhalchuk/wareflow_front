<?php

namespace App\Http\Requests\Web\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Category Store Request.
 */
class CategoryStoreRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $workspaceId = $this->user()?->current_workspace?->id
            ?? $this->user()?->workspace_id;

        return [
            'name' => [
                'required','string','max:255',
                Rule::unique('categories','name')
                    ->where(fn ($q) => $q->where('workspace_id', $workspaceId)),
            ],
            /** @ignoreParam */
            'active' => ['sometimes','boolean'],

            'parent_id' => [
                'nullable','uuid',
                Rule::exists('categories','id')
                    ->where(fn ($q) => $q->where('workspace_id', $workspaceId)),
            ],

            'goods_category_id' => [
                'nullable','integer',
                'required_without:parent_id',
                Rule::exists('_d_goods_categories','id'),
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Category name must be unique within the workspace.',
            'goods_category_id.required_without' => 'Goods category is required for root categories.',
        ];
    }
}
