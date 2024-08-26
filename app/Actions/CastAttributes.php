<?php


namespace App\Actions;

use Propaganistas\LaravelPhone\PhoneNumber;

class CastAttributes{
    public static function phoneNumber( $number, $country ){
        try {
            return (new PhoneNumber($number, $country))->formatE164();
        }
        catch ( \Exception $e ){}

        return $number;
    }
}
