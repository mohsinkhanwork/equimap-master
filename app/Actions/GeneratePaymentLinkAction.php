<?php


namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class GeneratePaymentLinkAction {
    public function handle( $bookable_type, $bookable_id, $date, $optional=[] ){
        $params = [
            'expiry'            => Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s'),
            'date'              => $date,
            'bookable_type'     => $bookable_type,
            'bookable_id'       => $bookable_id,
            'schedule_id'       => isset( $optional['schedule_id'] ) ? $optional['schedule_id'] : false,
            'horse_id'          => isset( $optional['horse_id'] ) ? $optional['horse_id'] : false,
            'trainer_id'        => isset( $optional['trainer_id'] ) ? $optional['trainer_id'] : false,
            'booking_id'        => isset( $optional['booking_id'] ) ? $optional['booking_id'] : false,
            'notes'             => isset( $optional['notes'] ) ? base64_encode( $optional['notes'] ) : false,
            'coupon'            => isset( $optional['coupon'] ) ? $optional['coupon'] : false,
            '_token'            => utils()->getAuthToken(),
        ];

        // add payment token to make sure we secure expiry time
        $params['pay_token']    = $this->getPaymentToken( $params );

        return route( 'pay', array_filter( $params ) );
    }

    public function getPaymentToken( $params ){
        return Hash::make( json_encode( $params ) );
    }
}
