<?php

namespace App\Http\Requests\Booking;

use App\Http\Requests\BaseRequest;
use App\Models\Booking;
use Illuminate\Validation\Rule;

class BookingRescheduleRequest extends BaseRequest{
    public function prepareForValidation(){
        $booking_id = $this->route('booking_id');
        if( $booking_id > 0 ){
            $booking    = Booking::find( $booking_id );
            if( $booking->id > 0 ){
                $this->mergeIfMissing( [ 'booking_id' => $booking_id, 'bookable_id' => $booking->bookable_id ] );
            }

            $this->merge([
                'package_type' => utils()->plural( utils()->getMorphableName( $booking->bookable_type ) )
            ]);
        }
    }

    public function rules(){
        return [
            'bookable_id'   => [ 'required', 'exists:' . $this->package_type . ',id,active,1,approved,1' ],
            'date'          => [ 'required', 'date', 'after:today', 'date_format:Y-m-d' ],
            'schedule_id'   => [ 'required', 'exists:schedules,id'],
            'horse_id'      => [ 'sometimes', 'exists:horses,id', Rule::exists('services_horses', 'horse_id' )->where( 'service_id', $this->service_id ) ],
            'trainer_id'    => [ 'sometimes', 'exists:trainers,id', Rule::exists('services_trainers', 'trainer_id' )->where( 'service_id', $this->service_id ) ],
            'booking_id'    => [ 'sometimes', 'exists:bookings,id,user_id,' . utils()->getUserId() ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/booking.availability.failed';
    }
}
