<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class TripController extends Controller {
    public function show( $trip_slug, $trip_id ){
        return redirect(env('APP_ONE_LINK'));
    }
}
