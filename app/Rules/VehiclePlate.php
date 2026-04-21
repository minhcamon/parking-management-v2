<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VehiclePlate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_valid_vehicle_plate($value)) {
            $fail('Biển số xe không đúng định dạng (VD: 29A-123.45 hoặc 29H1-123.45).');
        }
    }
}
