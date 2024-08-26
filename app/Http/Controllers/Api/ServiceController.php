<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ServiceController extends Controller {
    function index( Service $service ){
        $services   = $service
                        ->active()
                        ->orderByDesc('sort')
                        ->paginate();

        if( $services->isNotEmpty() ){
            return $this->success( 'api/service.index.success', $services );
        }

        return $this->notfound( 'api/service.index.no_results' );
    }

    function show( Service $service, $service_id ){
        $services   = $service
                        ->active()
                        ->where( 'id', $service_id )
                        ->with( [ 'horses', 'trainers', 'calendars' ] )
                        ->get();

        if( $services->isNotEmpty() ){
            $services               = $services->first();
            $services->schedules    = $services->schedulesGrouped();
            $services->append('reviews_summary');

            return $this->success( 'api/service.index.success', $services );
        }

        return $this->notfound( 'api/service.index.no_results' );
    }

    function store( Request $request, Service $services ){
        $validate   = Validator::make( $request->all(), [
            'name'          => [ 'required' ],
            'description'   => [ 'min: 25' ],
            'price'         => [ 'required', 'min:1' ],
            'unit'          => [ 'required', 'in:hour,day'],
            'capacity'      => [ 'sometimes', 'numeric', 'between:1,12'],
            'provider_id'   => [ 'required', 'exists:providers,id,user_id,' . utils()->getUserId() ],
            'category_id'   => [ 'required', 'exists:categories,id' ]
        ]);

        if( $validate->fails() ){
            return $this->error( 'api/service.create.failed', $validate->errors() );
        }

        return $services->createAndReturn( $request->all() );
    }

    function schedules( Service $service, $service_id ){
        $services   = $service
                            ->where( 'id', $service_id )
                            ->get();

        if( $services->isNotEmpty() ){
            $schedules = $services->first()->schedulesGrouped();
            if( $schedules->isNotEmpty() ){
                return $this->success( 'api/schedules.list.success', $schedules );
            }
        }

        return $this->notfound( 'api/schedules.list.no_results' );
    }

    function reviews( Service $service, $service_id ){
        $services   = $service
                        ->where( 'id', $service_id )
                        ->get();

        if( $services->isNotEmpty() ){
            $reviews = $services->first()->reviews()->paginate();
            if( $reviews->isNotEmpty() ){
                return $this->success( 'api/reviews.list.success', $reviews );
            }
        }

        return $this->notfound( 'api/reviews.list.no_results' );
    }
}
