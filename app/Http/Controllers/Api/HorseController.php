<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horse;

class HorseController extends Controller {
    function index( Horse $horse ){
        $horses = $horse
                    ->paginate();

        if( $horses->isNotEmpty() ){
            return $this->success( 'api/horse.index.success', $horses );
        }

        return $this->notfound( 'api/horse.index.no_results' );
    }
}
