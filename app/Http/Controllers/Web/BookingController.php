<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingCheckinService;
use Illuminate\Http\Request;

class BookingController extends Controller {
    public function checkin( Request $request, Booking $bookings ){
        if( $request->method() == 'GET' ){
            if( $request->has( 'reference') ){
                $service    = ( new BookingCheckinService() )->booking( $request->reference );

            }
        }

        return view( 'pages.bookings.checkin.start' );
    }

    public function start( Request $request ){
        $service    = ( new BookingCheckinService() )->booking( $request->reference );
        if( $service->invalidBooking() ){
            return redirect()->back()->with( 'error', __('api/booking.not_found'));
        }

        return $service;
    }
}
