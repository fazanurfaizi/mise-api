<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->isPhone($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Incorrect phone format for :atttibute');
    }

    /**
     * Checks through all validation methods to verify
     * it is in a phone number format of some type.
     *
     * @param string $value
     * @return bool
     */
    protected function isPhone(string $value): bool
    {
        return $this->isDigits($value) || $this->isE123($value) || $this->isE164($value) || $this->isNANP($value);
    }

    /**
     * Check for format like 5555555555, 15555555555.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isDigits(string $value): bool
    {
        $conditions = [];
        $conditions[] = mb_strlen($value) >= 10;
        $conditions[] = mb_strlen($value) <= 16;
        $conditions[] = preg_match('/[^\\d]/i', $value) === 0;

        return (bool) array_product($conditions);
    }

    /**
     * Check for format like +22 555 555 1234, (607) 555 1234, (022607) 555 1234.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isE123(string $value): bool
    {
        return preg_match('/^(?:\((\+?\d+)?\)|\+?\d+) ?\d*(-?\d{2,3} ?){0,4}$/', $value) === 1;
    }

    /**
     * Check for format like +15555555555.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isE164(string $value): bool
    {
        $conditions = [];
        $conditions[] = mb_strpos($value, '+') === 0;
        $conditions[] = mb_strlen($value) >= 9;
        $conditions[] = mb_strlen($value) <= 16;
        $conditions[] = preg_match('/[^\\d+]/i', $value) === 0;

        return (bool) array_product($conditions);
    }

    /**
     * Format examples: (555) 555-5555, 1 (555) 555-5555, 1-555-555-5555, 555-555-5555, 1 555 555-5555
     * https://en.wikipedia.org/wiki/National_conventions_for_writing_telephone_numbers#United_States.2C_Canada.2C_and_other_NANP_countries.
     *
     * @param  string  $value The phone number to check
     * @return bool is it correct format?
     */
    protected function isNANP(string $value): bool
    {
        $conditions = [];
        $conditions[] = preg_match('/^(?:\\+1|1)?\\s?-?\\(?\\d{3}\\)?(\\s|-)?\\d{3}-\\d{4}$/i', $value) > 0;

        return (bool) array_product($conditions);
    }
}
