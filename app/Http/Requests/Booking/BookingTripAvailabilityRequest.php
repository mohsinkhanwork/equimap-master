<?php

namespace App\Http\Requests\Booking;

use App\Http\Requests\BaseRequest;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingTripAvailabilityRequest extends BaseRequest{
    public function rules(){
        return [
            'bookable_type' => [ 'required', Rule::in( array_keys( Booking::getBookingTypes() ) ) ],
            'bookable_id'   => [ 'required', "exists:trips,id,active,1,approved,1" ],
            'horse_id'      => [ 'sometimes', 'exists:horses,id', Rule::exists('services_horses', 'horse_id' )->where( 'service_id', $this->service_id ) ],
            'trainer_id'    => [ 'sometimes', 'exists:trainers,id', Rule::exists('services_trainers', 'trainer_id' )->where( 'service_id', $this->service_id ) ],
        ];
    }

    protected function getResponseMessage(){
        return 'api/booking.prevalidation.failed';
    }
}
