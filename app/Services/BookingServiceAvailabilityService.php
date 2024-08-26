<?php

namespace App\Services;

use App\Actions\GeneratePaymentLinkAction;
use App\Models\Booking;
use App\Models\Calendar;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Exception;

class BookingServiceAvailabilityService{
    protected $bookable_type= 'service';
    protected $bookable_id  = null;
    protected $date         = null;
    protected $start_time   = null;
    protected $end_time     = null;
    protected $day          = null;
    protected $schedule_id  = null;
    protected $horse_id     = null;
    protected $trainer_id   = null;
    protected $booking_id   = null;
    protected $bookable     = null;
    protected $booking      = null;
    protected $calendars    = null;
    protected $bookings     = null;
    protected $notes        = null;
    protected $coupon       = null;
    protected $schedule     = null;

    public function setup( $date, $bookable_id, $schedule_id, $additional=[] ){
        $this->date         = $date;
        $this->day          = utils()->dayFromDate( $date );
        $this->bookable_id  = $bookable_id;
        $this->schedule_id  = $schedule_id;

        if( isset( $additional['horse_id'] ) ){
            $this->horse_id     = $additional['horse_id'];
        }

        if( isset( $additional['trainer_id'] ) ){
            $this->trainer_id   = $additional['trainer_id'];
        }

        if( isset( $additional['booking_id'] ) ){
            $this->booking_id   = $additional['booking_id'];
        }

        if( isset( $additional['notes'] ) ){
            $this->notes        = $additional['notes'];
        }

        if( isset( $additional['coupon'] ) ){
            $this->coupon       = $additional['coupon'];
        }

        return $this;
    }

    public function isSetup(){
        return $this->getDate()
            && $this->getBookableId()
            && $this->getScheduleId();
    }

    public function canSchedule(){
        return ( !$this->isScheduleNotActive()
            && !$this->isScheduleNotService()
            && !$this->isScheduleUnavailable()
            && !$this->isCalendarBlocked()
            && !$this->isServiceSlotReserved()
            && !$this->isHorseUnavailable()
            && !$this->isTrainerUnavailable()
        );
    }

    public function getServiceCharges(){
        $service        = $this->getBookable();
        $schedule       = $this->getSchedule();
        $calendar       = $this->getCalendars();
        $currency       = $service->currency;
        $priceTotal     = $service->price;
        $pricePaid      = 0;
        $couponTotal    = 0;

        // price for specific slot
        if( $schedule->price > 0 ){
            $priceTotal = $schedule->price;
        }

        // any markups or discounts on calendar
        if( $calendar->isNotEmpty() ){
            foreach( $calendar as $item ){
                if( $item->event_type == $item::TYPE_PRICE && $item->price > 0 ){
                    $priceTotal += $item->price;
                }
            }
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

            if( $this->getCoupon() == 'NL10' ){
                $couponTotal    = ( $priceBalance*0.10 );
                $couponTotal    = $couponTotal > 50 ? 50 : $couponTotal;
                $couponTotal    = $couponTotal * -1;
                $priceBalance   = $priceBalance + $couponTotal;
            }

            if( $this->getCoupon() == 'EID24' ){
                if( in_array( date('Y/m/d'), [ '2024/06/16', '2024/06/17', '2024/06/18', '2024/06/19', '2024/06/20' ] ) ){
                    $couponTotal    = ( $priceBalance*0.10 );
                    $couponTotal    = $couponTotal > 75 ? 75 : $couponTotal;
                    $couponTotal    = $couponTotal * -1;
                    $priceBalance   = $priceBalance + $couponTotal;
                }
            }
        }
        /**
         * END (TEMPORARY DISCOUNT COUPONS)
         */

        return [
            'schedule'  => [
                'date'       => $this->getDate(),
                'day'        => Service::getServiceDays()[ $this->getDay() ],
                'unit'       => $this->getBookable()->unit,
                'check_in'   => $this->getStartTime(),
                'check_out'  => $this->getEndTime(),
                'bookable_id'   => $this->getBookableId(),
                'bookable_type' => $this->getBookableType(),
                'horse_id'      => $this->getHorseId(),
                'trainer_id'    => $this->getTrainerId(),
                'schedule_id'   => $this->getScheduleId(),
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

    public function isPaymentRequired( $priceBalance ){
        return utils()->getUserId() > 0 && $priceBalance > 0;
    }

    public function getPaymentUrl(){
        return ( new GeneratePaymentLinkAction() )->handle( $this->getBookableType(), $this->getBookableId(), $this->getDate(), [
            'schedule_id'   => $this->getScheduleId(),
            'horse_id'      => $this->getHorseId(),
            'trainer_id'    => $this->getTrainerId(),
            'booking_id'    => $this->getBookingId(),
            'notes'         => $this->getNotes(),
            'coupon'        => $this->getCoupon()
        ] );
    }

    public function isScheduleNotActive(){
        $schedule               = $this->getSchedule();

        return empty( $schedule ) || $schedule->active !== 1;
    }

    public function isScheduleNotService(){
        if( !$this->isSetup() ){
            throw new Exception( 'Booking availability service is missing parameters.');
        }

        if( $this->isScheduleNotActive() ){
            return false;
        }

        $schedule   = $this->getSchedule();
        return $schedule->getScheduleType() != 'service';
    }

    public function isScheduleUnavailable(){
        if( !$this->isSetup() ){
            throw new Exception( 'Booking availability service is missing parameters.');
        }

        if( $this->isScheduleNotActive() === false ){
            return false;
        }

        $schedule   = $this->getSchedule();
        return empty( $schedule ) || strtolower( $schedule->day ) != strtolower( $this->getDay() );
    }

    public function isCalendarBlocked(){
        if( !$this->isSetup() ){
            throw new Exception( 'Booking availability service is missing parameters.');
        }

        // Lets fetch data from calendar for block events
        $calendars  = $this->getCalendars();

        return $calendars->isNotEmpty() ? $calendars->isBlocked() : false;
    }

    public function isServiceSlotReserved(){
        if( !$this->isSetup() ){
            throw new Exception( 'Booking availability service is missing parameters.');
        }

        $bookings   = $this->getBookings();
        if( $bookings->isNotEmpty()
            && $bookings->isService( $this->getBookableId() )->count() >= $this->getBookable()->capacity ){
            return true;
        }
    }

    public function isHorseUnavailable(){
        if( !$this->isSetup() ){
            throw new Exception( 'Booking availability service is missing parameters.');
        }

        $bookings   = $this->getBookings();
        if( $bookings->isNotEmpty()
            && $bookings->isHorse( $this->getHorseId() )->count() > 0 ){
            return true;
        }
    }

    public function isTrainerUnavailable(){
        if( !$this->isSetup() ){
            throw new Exception( 'Booking availability service is missing parameters.');
        }

        $bookings   = $this->getBookings();
        if( $bookings->isNotEmpty()
            && $bookings->isTrainer( $this->getTrainerId() )->count() > 0 ){
            return true;
        }
    }

    public function getBookable(){
        if( $this->bookable == null ){
            $this->bookable = Service::find( $this->getBookableId() );
        }

        return $this->bookable;
    }

    public function getSchedule(){
        if( is_null( $this->schedule ) ){
            $this->schedule = Schedule::find( $this->getScheduleId() );
        }

        return $this->schedule ? $this->schedule : false;
    }

    public function getCalendars(){
        if( is_null( $this->calendars ) ){
            $this->calendars    = Calendar::dateTime( $this->getStartDateTime(), $this->getEndDateTime() )
                ->combined(
                    $this->getBookableId(),
                    $this->getHorseId(),
                    $this->getTrainerId()
                )
                ->get();

        }
        return $this->calendars;
    }

    public function getBooking(){
        if( is_null( $this->booking ) && $this->getBookingId() > 0 ) {
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
            $this->bookings = Booking::dateTime( $this->getStartDateTime(), $this->getEndDateTime() )
                ->combined(
                    $this->getBookableId(),
                    $this->getHorseId(),
                    $this->getTrainerId()
                )
                ->get();
        }

        return $this->bookings;
    }

    public function getScheduleId(){
        return $this->schedule_id;
    }

    public function getStartDateTime(){
        return Carbon::parse( "{$this->getDate()} {$this->getStartTime()}");
    }

    public function getEndDateTime(){
        return Carbon::parse( "{$this->getDate()} {$this->getEndTime()}");
    }

    public function getStartTime(){
        if( is_null( $this->start_time ) ){
            $schedule           = $this->getSchedule();
            $this->start_time   = $schedule->start_time;
        }

        return $this->start_time;
    }

    public function getEndTime(){
        if( is_null( $this->end_time ) ){
            $schedule       = $this->getSchedule();
            $this->end_time = $schedule->end_time;
        }

        return $this->end_time;
    }

    public function getDay(){
        return $this->day;
    }

    public function getDate(){
        return $this->date;
    }

    public function getBookableId(){
        return $this->bookable_id;
    }

    public function getHorseId(){
        return $this->horse_id;
    }

    public function getTrainerId(){
        return $this->trainer_id;
    }

    public function getBookingId(){
        return $this->booking_id;
    }

    public function getNotes(){
        return $this->notes;
    }

    public function getCoupon(){
        return in_array( strtoupper( $this->coupon ), [ 'EID24', 'WOMEN', 'NL10', 'EMEC-25', 'JOY', 'FIRST', 'FREEPLAY' ] ) ? strtoupper( $this->coupon ) : null;
    }

    public function getBookableType(){
        return $this->bookable_type;
    }
}
