<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainer;

class TrainerController extends Controller {
    function index( Trainer $trainer ){
        $trainers = $trainer
                        ->paginate();

        if( $trainers->isNotEmpty() ){
            return $this->success( 'api/trainer.index.success', $trainers );
        }

        return $this->notfound( 'api/trainer.index.no_results' );
    }
}
