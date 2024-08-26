<?php

namespace App\Actions;

use App\Models\Booking;

class GeneratePaymentCode {
    public static function handle(){
        $code       = self::generateCode();
        $exists     = Booking::where( 'reference', $code )->get();

        return $exists->isNotEmpty() ? self::handle() : $code;
    }

    protected static function generateCode(){
        $length     = config( 'general.payment_code_length' );
        return strtoupper( utils()->randomStr( $length ) );
    }
}
