<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRut implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        // Remove dashes and dots from the RUT and convert to uppercase
        $rut = strtoupper(str_replace(['.'], '', $value));

        // Validate the format of the RUT
        if (!preg_match('/^[0-9]+-[0-9kK]$/', $rut)) {
            $fail("El formato del rut es inválido.");
            return;
        }

        // Separate the number from the verification digit
        [$num, $dv] = explode('-', $rut);

        // Calculate the expected verification digit
        $factor = 2;
        $suma = 0;
        for ($i = strlen($num) - 1; $i >= 0; $i--) {
            $suma += $factor * $num[$i];
            $factor = $factor == 7 ? 2 : $factor + 1;
        }
        $dvExpected = 11 - ($suma % 11);
        if ($dvExpected == 11) {
            $dvExpected = '0';
        } elseif ($dvExpected == 10) {
            $dvExpected = 'K';
        }

        // Verify if the verification digit matches
        if ($dv != $dvExpected) {
            $fail("El RUT es inválido.");
        }
    }
}
// Path: API/app/Http/Requests/Auth/CreateRequest.php
