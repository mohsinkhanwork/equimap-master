<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FbaTokenRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return utils()->getPhoneFromFbaToken( $value );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('api/general.fba_token_invalid');
    }
}
