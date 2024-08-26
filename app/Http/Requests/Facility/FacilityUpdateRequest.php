<?php

namespace App\Http\Requests\Facility;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class FacilityUpdateRequest extends BaseRequest{
    public function rules(){
        // get category id that we are updating and merge with request
        $facility_id    = $this->route('facility_id');
        if( $facility_id > 0 ){
            $this->mergeIfMissing( [ 'facility_id' => $facility_id ] );
        }

        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('facilities', 'name')->ignore( $facility_id );

        return [
            'name'  => [ 'required', 'string', 'min: 4', $uniqueRule ],
            'sort'  => [ 'numeric', 'gte:0' ],
            'icon'  => [ 'sometimes', File::image()->max(2000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/facility.update.failed';
    }
}
