<?php

namespace App\Http\Requests\Booking;

use App\Http\Requests\BaseRequest;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingPackageAvailabilityRequest extends BaseRequest{
    public function rules(){
        return [
            'bookable_type' => [ 'required', Rule::in( array_keys( Booking::getBookingTypes() ) ) ],
            'bookable_id'   => [ 'required', "exists:packages,id,active,1,approved,1" ],
        ];
    }

    protected function getResponseMessage(){
        return 'api/booking.prevalidation.failed';
    }
}
