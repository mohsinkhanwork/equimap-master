<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FcmTokenRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value){
        return utils()->validateFcmToken( $value );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(){
        return __('api/general.fcm_token_invalid');
    }
}
