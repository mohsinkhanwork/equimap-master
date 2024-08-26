<?php

namespace App\Models;

class TripsItinerariesPlace extends Model{
    protected $fillable     = [
        'trip_itinerary_id',
        'name',
        'address',
        'city',
        'country'
    ];

    public function trip_itinerary(){
        return $this->belongsTo( 'App\Models\TripItinerary', 'trip_itinerary_id', 'id' );
    }
}
