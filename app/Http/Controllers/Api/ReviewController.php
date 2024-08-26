<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller {
    function store( Request $request, Review $reviews ){
        $validate   = Validator::make( $request->all(), [
            'rate'          => [ 'required', 'integer', 'between:1,5' ],
            'review'        => [ 'required' ],
            'service_id'    => [ 'required', 'exists:services,id' ]
        ]);

        if( $validate->fails() ){
            return $this->error( 'api/reviews.create.failed' );
        }

        return $reviews->createAndReturn(
            $request->all(),
            'api/reviews.create.success',
            'api/reviews.create.failed'
        );
    }
}
