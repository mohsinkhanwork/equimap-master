<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PaymentTokenRule implements Rule
{
    /**
     * @var Request
     */
    private $request;

    /**
     * Create a new rule instance.
     *
     * @param Request $request
     */
    public function __construct( Request $request ){
        $this->request  = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes( $attribute, $value )
    {
        $reqSansPayToken    = $this->request->except('pay_token');
        return Hash::check( json_encode( $reqSansPayToken ), $value );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('api/general.pay_token_invalid');
    }
}
