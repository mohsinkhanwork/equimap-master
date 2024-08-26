<?php

namespace App\Services;

use App\Actions\GeneratePaymentLinkAction;
use App\Models\Booking;
use App\Models\Package;
use Carbon\Carbon;

class BookingPackageAvailabilityService{
    protected $bookable_type= 'package';
    protected $bookable_id  = null;
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
        $currency       = $bookable->packageable()->first()->currency;
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

        /**
         * Temporary hard coded coupons,
         * should be removed after implementing proper
         * discount module.
         */
        if( $this->getCoupon() ){
            if( $this->getCoupon() == 'NL10' ){
                $couponTotal    = ( $priceBalance*0.10 );
                $couponTotal    = $couponTotal > 50 ? 50 : $couponTotal;
                $couponTotal    = $couponTotal * -1;
                $priceBalance   = $priceBalance + $couponTotal;
            }
        }
        /**
         * END (TEMPORARY DISCOUNT COUPONS
         */

        return [
            'schedule'  => [
                'bookable_type' => $this->getBookableType(),
                'bookable_id'   => $this->getBookableId(),
                'booking_id'    => $this->getBookingId()
            ],
            'booking'   => [
                'available'         => true,
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
        return true;
    }

    public function isPaymentRequired( $priceBalance ){
        return utils()->getUserId() > 0 && $priceBalance > 0;
    }

    public function getPaymentUrl(){
        return ( new GeneratePaymentLinkAction() )
            ->handle( $this->getBookableType(), $this->getBookableId(), Carbon::now()->addDays(90),[
            'booking_id'=> $this->getBookingId(),
            'notes'     => $this->getNotes()
        ]);
    }

    public function getBookable(){
        if( $this->bookable == null ){
            $bookable_id    = $this->getBookableId();
            $this->bookable = Package::find( $bookable_id );
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
        return in_array( strtoupper( $this->coupon ), [ 'NL10' ] ) ? $this->coupon : null;
    }
}
