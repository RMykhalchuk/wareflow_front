<?php

namespace App\Http\Requests\Web\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class LeftoversSyncByItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $items = $this->input('items', []);
        if (is_array($items)) {
            foreach ($items as $i => $item) {
                if (array_key_exists('condition', $item) && !is_bool($item['condition'])) {
                    $normalized = filter_var($item['condition'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($normalized === null) {
                        unset($items[$i]['condition']);
                    } else {
                        $items[$i]['condition'] = $normalized;
                    }
                }
            }
            $this->merge(['items' => $items]);
        }
    }

    public function rules(): array
    {
        return [
            'items'                               => ['sometimes', 'array'],

            // UPDATE (частковий): коли є id — усі інші поля optional (sometimes)
            'items.*.id'                          => ['sometimes', 'uuid', 'exists:inventory_leftovers,id'],

            // CREATE: коли id немає — ці поля обов’язкові
            'items.*.inventory'                   => ['required_without:items.*.id', 'uuid', 'exists:inventories,id'],
            'items.*.goods_id'                    => ['required_without:items.*.id', 'uuid', 'exists:goods,id'],
            'items.*.quantity'                    => ['required_without:items.*.id', 'integer', 'min:1'],
            'items.*.batch'                       => ['required_without:items.*.id', 'string'],
            'items.*.manufacture_date'            => ['required_without:items.*.id', 'date', 'date_format:Y-m-d'],
            'items.*.expiration_term'             => ['required_without:items.*.id', 'integer'],
            'items.*.bb_date'                     => ['required_without:items.*.id', 'date', 'date_format:Y-m-d'],
            'items.*.packages_id'                 => ['required_without:items.*.id', 'uuid', 'exists:packages,id'],

            // Під час CREATE може бути передано, під час UPDATE — часткове
            'items.*.container_registers_id'      => ['sometimes', 'nullable', 'uuid', 'exists:container_registers,id'],
            'items.*.condition'                   => ['sometimes', 'boolean'],
        ];
    }

    // Завжди JSON-відповіді
    public function wantsJson(): bool
    {
        return true;
    }
}
