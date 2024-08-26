<?php

namespace App\Services;

use App\Actions\GeneratePaymentLinkAction;
use App\Models\Booking;
use App\Models\Trip;

class BookingTripAvailabilityService{
    protected $bookable_type= 'trip';
    protected $bookable_id  = null;
    protected $start_date   = null;
    protected $end_date     = null;
    protected $booking_id   = null;
    protected $bookable     = null;
    protected $booking      = null;
    protected $bookings     = null;
    protected $notes        = null;
    protected $coupon       = null;

    public function setup( $request ){
        $this->bookable_id  = $request->bookable_id;

        if( $request->has('booking_id') ){
            $this->booking_id   = $request->booking_id;
        }

        if( $request->has('notes') ){
            $this->notes        = $request->notes;
        }

        if( $request->has('coupon') ){
            $this->coupon       = $request->coupon;
        }

        return $this;
    }

    public function getServiceCharges(){
        $bookable       = $this->getBookable();
        $currency       = $bookable->currency;
        $priceTotal     = $bookable->price;
        $pricePaid      = 0;
        $couponTotal    = 0;

        // price for specific slot
        if( $bookable->price > 0 ){
            $priceTotal = $bookable->price;
        }

        // if bookings are available then count paid amount
        if( $this->getBooking() ){
            $pricePaid      = $this->getBooking()->transactions()->sum('amount');
            if( $this->getBooking()->parent()->count() > 0 ){
                $pricePaid  = $priceTotal;
            }
        }

        // final balance amount to be paid
        $priceBalance   = $priceTotal - $pricePaid;

        // if coupon is available calculate discount
        if( $this->getCoupon() ){
            if( $this->getCoupon() == 'FIRST' ){
                $existingBooking    = Booking::whereIn('status', ['completed','scheduled'])
                    ->where( 'user_id', utils()->getUserId() )
                    ->get();

                if( $existingBooking->isEmpty() ){
                    $couponTotal    = ( $priceBalance*0.15 );
                    $couponTotal    = $couponTotal > 50 ? 50 : $couponTotal;
                    $couponTotal    = $couponTotal * -1;
                    $priceBalance   = $priceBalance + $couponTotal;
                }
            }

            if( $this->getCoupon() == 'TRIP15' ) {
                $couponTotal = ($priceBalance * 0.1);
                $couponTotal = $couponTotal > 75 ? 75 : $couponTotal;
                $couponTotal = $couponTotal * -1;
                $priceBalance = $priceBalance + $couponTotal;
            }

            if( $this->getCoupon() == 'JOY' ) {
                $couponTotal = 50;
                $couponTotal = $couponTotal * -1;
                $priceBalance = $priceBalance + $couponTotal;
            }

            if( $this->getCoupon() == 'EMEC-25' ) {
                $couponTotal = 25;
                $couponTotal = $couponTotal * -1;
                $priceBalance = $priceBalance + $couponTotal;
            }

            if( $this->getCoupon() == 'NL10' ){
                $couponTotal    = ( $priceBalance*0.10 );
                $couponTotal    = $couponTotal > 50 ? 50 : $couponTotal;
                $couponTotal    = $couponTotal * -1;
                $priceBalance   = $priceBalance + $couponTotal;
            }
        }

        return [
            'schedule'  => [
                'check_in'      => $this->getStartDate(),
                'check_out'     => $this->getEndDate(),
                'bookable_type' => $this->getBookableType(),
                'bookable_id'   => $this->getBookableId(),
                'booking_id'    => $this->getBookingId()
            ],
            'booking'   => [
                'available'         => true,
                'capacity'          => $this->getBookable()->capacity,
                'booked'            => $this->getBookings()->count(),
                'payment_required'  => $this->isPaymentRequired( $priceBalance ),
                'payment_url'       => $this->isPaymentRequired( $priceBalance ) ? $this->getPaymentUrl() : false,
            ],
            'price'     => [
                'total'     => floatval( $priceTotal ),
                'paid'      => floatval( $pricePaid ),
                'coupon'    => floatval( round( $couponTotal, 2) ),
                'balance'   => floatval( $priceBalance ),
                'currency'  => $currency
            ],
            'notes'     => $this->getNotes()
        ];
    }

    public function canSchedule(){
        return !$this->isServiceSlotReserved();
    }

    public function isPaymentRequired( $priceBalance ){
        return utils()->getUserId() > 0 && $priceBalance > 0;
    }

    public function getPaymentUrl(){
        return ( new GeneratePaymentLinkAction() )->handle( $this->getBookableType(), $this->getBookableId(), $this->getStartDate(), [
            'booking_id'=> $this->getBookingId(),
            'notes'     => $this->getNotes(),
            'coupon'    => $this->getCoupon()
        ] );
    }

    public function isServiceSlotReserved(){
        $bookings   = $this->getBookings();
        if( $bookings->isNotEmpty()
            && $bookings->isTrip( $this->getBookableId() )->count() >= $this->getBookable()->capacity ){
            return true;
        }
    }

    public function getBookable(){
        if( $this->bookable == null ){
            $bookable_id    = $this->getBookableId();
            $this->bookable = Trip::find( $bookable_id );

            // setup dates
            $this->start_date   = $this->bookable->start_date;
            $this->end_date     = $this->bookable->end_date;
        }

        return $this->bookable;
    }

    public function getBooking(){
        if( is_null( $this->booking ) && $this->getBookingId() > 0 ){
            $booking = Booking::find($this->getBookingId());
            if( $booking ){
                $booking    = $booking->where([
                    'id'            => $this->getBookingId(),
                    'user_id'       => auth()->guard('sanctum')->id(),
                    'bookable_type' => "App\Models\Service",
                    'bookable_id'   => $this->getBookableId(),
                ])->whereIn('status', ['scheduled', 'pending'])->get();
                $this->booking  = $booking->first();
            }
        }

        return $this->booking;
    }

    public function getBookings(){
        if( is_null( $this->bookings ) ){
            $this->bookings = Booking::where('bookable_id', $this->getBookableId() )->get();
        }

        return $this->bookings;
    }

    public function getStartDate(){
        return $this->start_date;
    }

    public function getEndDate(){
        return $this->end_date;
    }

    public function getBookableType(){
        return $this->bookable_type;
    }

    public function getBookableId(){
        return $this->bookable_id;
    }

    public function getBookingId(){
        return $this->booking_id;
    }

    public function getNotes(){
        return $this->notes;
    }

    public function getCoupon(){
        return in_array( strtoupper( $this->coupon ), [ 'NL10', 'EMEC-25', 'JOY', 'FIRST', 'TRIP15' ] ) ? $this->coupon : null;
    }
}
