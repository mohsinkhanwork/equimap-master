<?php

namespace App\Http\Requests\Trip;

use App\Http\Requests\BaseRequest;
use App\Models\Trip;
use Illuminate\Validation\Rule;

class TripIndexRequest extends BaseRequest{
    public function prepareForValidation(){
        $this->mergeIfMissing( [ 'type' => 'travel' ] );
    }

    public function rules(){
        return [
            'type'  => [ 'required', Rule::In( array_keys( Trip::getTripTypes() ) ) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/trip.create.failed';
    }
}
