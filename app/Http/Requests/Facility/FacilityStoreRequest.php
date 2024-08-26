<?php

namespace App\Http\Requests\Facility;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\File;

class FacilityStoreRequest extends BaseRequest{
    public function rules(){
        return [
            'name'  => [ 'required', 'string', 'min: 4', 'unique:facilities,name' ],
            'sort'  => [ 'numeric', 'gte:0' ],
            'icon'  => [ 'sometimes', File::image()->max(2000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/facility.create.failed';
    }
}
