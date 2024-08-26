<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trip\TripIndexRequest;
use App\Models\Trip;

class TripController extends Controller {
    function index( TripIndexRequest $request, Trip $trip ){
        $trips  = $trip
                        ->active()
                        ->type( $request->type )
                        ->orderByDesc('sort')
                        ->paginate();

        if( $trips->isNotEmpty() ){
            return $this->success( 'api/trip.index.success', $trips );
        }

        return $this->notfound( 'api/trip.index.no_results' );
    }

    function show( Trip $trip, $trip_id ){
        $trips   = $trip
                        ->where( 'id', $trip_id )
                        ->active()
                        ->with( [ 'images', 'itinerary' ] )
                        ->get();

        if( $trips->isNotEmpty() ){
            $trips               = $trips->first();
            return $this->success( 'api/trip.index.success', $trips );
        }

        return $this->notfound( 'api/trip.index.no_results' );
    }
}
