<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\BaseRequest;

class ServiceStoreRequest extends BaseRequest{
    public function prepareForValidation(){
        $this->mergeIfMissing( [ 'active' => 0, 'notes' => config('general.service.default_notes') ] );
    }

    public function rules(){
        return [
            'active'        => [ 'required', 'in:0,1' ],
            'sort'          => [ 'sometimes', 'numeric', 'gte:0'],
            'name'          => [ 'required' ],
            'description'   => [ 'min: 25' ],
            'price'         => [ 'required', 'numeric', 'min:1,max:10000' ],
            'unit'          => [ 'required', 'in:hour,day' ],
            'capacity'      => [ 'sometimes', 'numeric', 'between:1,12'],
            'provider_id'   => [ 'required', 'exists:providers,id' ],
            'category_id'   => [ 'required', 'exists:categories,id' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/service.create.failed';
    }
}
