<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotAllSameCharacters implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && strlen($value) > 0 && strlen(count_chars($value, 3)) === 1) {
            $fail(__('validation.password.not_all_same'));
        }
    }
}