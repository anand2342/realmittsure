<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueSlNo implements ValidationRule
{
    private $existingSlNos;

    // Constructor to accept existing serial numbers as an array
    public function __construct(array $existingSlNos)
    {
        $this->existingSlNos = $existingSlNos;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute  The name of the attribute being validated
     * @param  mixed   $value  The value of the attribute being validated
     * @param  \Closure  $fail  The callback to call if validation fails
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value exists in the array of existing serial numbers
        if (in_array($value, $this->existingSlNos)) {
            $fail('The :attribute must be unique.');
        }
    }
}
