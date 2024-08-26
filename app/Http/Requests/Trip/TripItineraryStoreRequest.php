<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\BaseRequest;

class TripItineraryStoreRequest extends BaseRequest{
    public function prepareForValidation(){
        // get trip id that we are updating and merge with request
        $trip_id    = $this->route('trip_id');
        if( $trip_id > 0 ){
            $this->mergeIfMissing( [ 'trip_id' => $trip_id ] );
        }
    }

    public function rules(){
        return [
            'trip_id'               => [ 'required', 'exists:trips,id'],
            'trip'                  => [ 'required', 'array' ],
            'trip.*.id'             => [ 'sometimes', 'exists:trips_itineraries,id' ],
            'trip.*.description'    => [ 'required', 'min:25' ],
        ];
    }

    protected function getResponseMessage(){
        return 'api/trip.create.failed';
    }
}
