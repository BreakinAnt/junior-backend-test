<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneIsValid implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove all non-digit characters for validation
        $phoneDigits = preg_replace('/\D/', '', $value);

        // Check if phone has the correct number of digits
        // Brazilian cellphones: 11 digits (with area code) or 10 digits (old format)
        if (strlen($phoneDigits) < 10 || strlen($phoneDigits) > 11) {
            $fail('The :attribute must be a valid Brazilian phone number with 10 or 11 digits.');
            return;
        }

        // Check if it's a valid Brazilian cellphone pattern
        // Format: (XX) 9XXXX-XXXX or (XX) XXXXX-XXXX
        // Where XX is the area code (11-99)
        if (strlen($phoneDigits) === 11) {
            // New format: 11 digits - must start with area code and 9
            if (!preg_match('/^[1-9][1-9]9[0-9]{8}$/', $phoneDigits)) {
                $fail('The :attribute must be a valid Brazilian cellphone number (11 digits starting with area code and 9).');
                return;
            }
        } elseif (strlen($phoneDigits) === 10) {
            // Old format: 10 digits - area code + 8 digits
            if (!preg_match('/^[1-9][1-9][0-9]{8}$/', $phoneDigits)) {
                $fail('The :attribute must be a valid Brazilian phone number (10 digits starting with area code).');
                return;
            }
        }

        // Additional validation for common formatting
        // Accept formats: (XX) 9XXXX-XXXX, (XX) XXXXX-XXXX, XX9XXXXXXXX, XX9XXXXXXXX
        if (!preg_match('/^(\([1-9][1-9]\)\s?|[1-9][1-9])?9?[0-9]{4,5}[\s\-]?[0-9]{4}$/', $value) && 
            !preg_match('/^[1-9][1-9]9[0-9]{8}$/', $phoneDigits) && 
            !preg_match('/^[1-9][1-9][0-9]{8}$/', $phoneDigits)) {
            $fail('The :attribute must be in a valid Brazilian phone format.');
            return;
        }
    }
}
