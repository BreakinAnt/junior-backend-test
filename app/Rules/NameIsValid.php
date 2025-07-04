<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NameIsValid implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $name = trim($value);

        if (strlen($name) < 2) {
            $fail('The :attribute must be at least 2 characters long.');
            return;
        }

        if(count(explode(' ', $name)) < 2) {
            $fail('The :attribute must contain at least 2 names.');
            return;
        }

        if (!preg_match('/^[a-zA-Z\s\-\']+$/', $name)) {
            $fail('The :attribute may only contain letters, spaces, hyphens, and apostrophes.');
            return;
        }

        if (!preg_match('/[a-zA-Z]/', $name)) {
            $fail('The :attribute must contain at least one letter.');
            return;
        }
    }
}
