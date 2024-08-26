<?php

namespace App\Services;

use App\Exceptions\InvalidBookingException;
use App\Models\Booking;

class BookingCheckinService{
    protected $booking;
    protected $reference;

    public function booking( $reference ){
        $booking    = Booking::where('reference', $reference)->get();

        if( $booking->isEmpty() ){
                throw new InvalidBookingException( 'api/booking.not_found', 412 );
        }
        else{
            $this->booking  = $booking->first();
        }

        return $this;
    }
}
