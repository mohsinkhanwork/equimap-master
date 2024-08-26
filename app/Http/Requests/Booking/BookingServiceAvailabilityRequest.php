<?php

namespace App\Http\Requests\Booking;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class BookingServiceAvailabilityRequest extends BaseRequest{
    public function prepareForValidation(){
        if( $this->service_id ){
            $this->merge([
                'bookable_id' => $this->service_id
            ]);
        }
    }
    public function rules(){
        return [
            'bookable_id'    => [ 'required', 'exists:services,id,active,1,approved,1' ],
            'date'          => [ 'required', 'date', 'after_or_equal:today', 'date_format:Y-m-d' ],
            'schedule_id'   => [ 'required', 'exists:schedules,id'],
            'horse_id'      => [ 'sometimes', 'exists:horses,id', Rule::exists('services_horses', 'horse_id' )->where( 'service_id', $this->bookable_id ) ],
            'trainer_id'    => [ 'sometimes', 'exists:trainers,id', Rule::exists('services_trainers', 'trainer_id' )->where( 'service_id', $this->bookable_id ) ],
        ];
    }

    protected function getResponseMessage(){
        return 'api/booking.availability.failed';
    }
}
