<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\BaseRequest;
use App\Models\Trip;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class TripUpdateRequest extends BaseRequest{
    public function rules(){
        // get trip id that we are updating and merge with request
        $trip_id    = $this->route('trip_id');
        if( $trip_id > 0 ){
            $this->mergeIfMissing( [ 'trip_id' => $trip_id ] );
        }

        // approval and notes
        if( !auth()->check() || auth()->guard('web')->user()->cannot('approve trip') ){
            $this->request->remove('approved');
            $this->request->remove('notes');
        }
        else{
            if( $this->has('approved') && $this->approved == 1 ){
                $this->merge( [ 'notes' => null ] );
            }
            else{
                $this->merge( [ 'approved' => 0 ] );
            }
        }

        // active check
        $this->mergeIfMissing( ['active' => $this->request->has('active') ? 1 : 0 ] );

        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('trips')
                                    ->ignore( $trip_id )
                                    ->where('provider_id', $this->request->get('provider_id'));

        return [
            'active'        => [ 'required', 'in:0,1' ],
            'approved'      => [ 'in:0,1' ],
            'sort'          => [ 'sometimes', 'numeric', 'gte:0'],
            'name'          => [ 'required', $uniqueRule ],
            'description'   => [ 'min: 25' ],
            'included_items'=> [ 'sometimes' ],
            'excluded_items'=> [ 'sometimes' ],
            'price'         => [ 'required', 'numeric', 'min:1,max:10000' ],
            'start_date'    => [ 'required', 'date', 'after:today', 'date_format:Y-m-d' ],
            'end_date'      => [ 'required', 'date', 'after:today', 'date_format:Y-m-d' ],
            'capacity'      => [ 'sometimes', 'numeric', 'between:1,500'],
            'provider_id'   => [ 'required', 'exists:providers,id' ],
            'category_id'   => [ 'required', 'exists:categories,id' ],
            'origin_country_id'         => [ 'required', 'exists:countries,id' ],
            'destination_country_id'    => [ 'required', 'exists:countries,id' ],
            'notes'         => [ 'sometimes' ],
            'gallery.*'     => [ 'sometimes', File::image()->max(5000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/trip.update.failed';
    }
}
