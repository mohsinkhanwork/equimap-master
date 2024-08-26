<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\BaseRequest;
use App\Models\Trip;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class TripStoreRequest extends BaseRequest{
    public function prepareForValidation(){
        $this->mergeIfMissing( [ 'active' => 0 ] );
    }

    public function rules(){
        return [
            'active'        => [ 'required', 'in:0,1' ],
            'sort'          => [ 'sometimes', 'numeric', 'gte:0'],
            'name'          => [ 'required', 'unique:trips,name' ],
            'description'   => [ 'required', 'min: 25' ],
            'type'          => [ 'required', Rule::In( array_keys( Trip::getTripTypes() ) ) ],
            'included_items'=> [ 'sometimes' ],
            'excluded_items'=> [ 'sometimes' ],
            'price'         => [ 'required', 'numeric', 'min:1,max:10000' ],
            'start_date'    => [ 'required', 'date', 'after:today', 'date_format:Y-m-d' ],
            'end_date'      => [ 'required', 'date', 'after_or_equal:start_date', 'date_format:Y-m-d' ],
            'capacity'      => [ 'sometimes', 'numeric', 'between:1,500'],
            'provider_id'   => [ 'required', 'exists:providers,id' ],
            'category_id'   => [ 'required', 'exists:categories,id' ],
            'gallery.*'                => [ 'sometimes', File::image()->max(5000) ],
            'origin_country_id'        => [ 'required', 'exists:countries,id' ],
            'destination_country_id'   => [ 'required', 'exists:countries,id' ],
        ];
    }

    protected function getResponseMessage(){
        return 'api/trip.create.failed';
    }
}
