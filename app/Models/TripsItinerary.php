<?php

namespace App\Models;

use App\Collections\TripsItineraryCollection;

class TripsItinerary extends Model{
    protected $fillable     = [
        'trip_id',
        'date',
        'description'
    ];
    protected $casts        = [
        'date'  => 'date:Y-m-d',
    ];

    public function trip(){
        return $this->belongsTo( 'App\Models\Trip', 'trip_id', 'id' );
    }

    public function newCollection( array $models = [] ){
        return ( new TripsItineraryCollection( $models ) );
    }
}
