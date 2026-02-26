<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Auth Check Pin Put Request.
 */
class AuthCheckPinPutRequest extends FormRequest
{
    /**
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Map alternative client fields before validation:
     * - new_pin         -> pin
     * - new_pin_repeat  -> pin_confirmation
     */
    protected function prepareForValidation(): void
    {
        $pin     = $this->input('pin', $this->input('new_pin'));
        $pinConf = $this->input('pin_confirmation', $this->input('new_pin_repeat'));

        if ($pin !== null) {
            $this->merge(['pin' => trim((string) $pin)]);
        }
        if ($pinConf !== null) {
            $this->merge(['pin_confirmation' => trim((string) $pinConf)]);
        }
        if ($this->has('old_pin')) {
            $this->merge(['old_pin' => trim((string) $this->input('old_pin'))]);
        }
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'old_pin'           => ['required', 'regex:/^\d{4}$/'],
            'pin'               => ['required', 'regex:/^\d{4}$/', 'confirmed', 'different:old_pin'],
            'pin_confirmation'  => ['required'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'old_pin.required'  => 'old_pin is required.',
            'old_pin.regex'     => 'old_pin must be exactly 4 digits.',
            'pin.required'      => 'pin is required.',
            'pin.regex'         => 'pin must be exactly 4 digits.',
            'pin.confirmed'     => 'pin confirmation does not match.',
            'pin.different'     => 'new pin must differ from old_pin.',
            'pin_confirmation.required' => 'pin_confirmation is required.',
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'old_pin' => 'old PIN',
            'pin' => 'new PIN',
            'pin_confirmation' => 'new PIN confirmation',
        ];
    }
}
