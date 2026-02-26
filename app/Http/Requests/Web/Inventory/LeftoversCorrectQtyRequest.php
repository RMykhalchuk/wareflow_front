<?php

namespace App\Http\Requests\Web\Inventory;

use Illuminate\Foundation\Http\FormRequest;

/**
 * LeftoversCorrectQtyRequest.
 */
class LeftoversCorrectQtyRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'quantity'   => ['required', 'numeric'],
            'package_id' => ['nullable', 'uuid'],
        ];
    }

    /**
     * @return float
     */
    public function quantity(): float
    {
        return (float) $this->get('quantity');
    }

    /**
     * @return string|null
     */
    public function packageId(): ?string
    {
        $value = $this->get('package_id');

        return $value !== null ? (string) $value : null;
    }
}
