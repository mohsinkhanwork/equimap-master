<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\BaseRequest;

class ServiceUpdateRequest extends BaseRequest{
    public function prepareForValidation(){
        if( auth()->guard('web')->user()->cannot('approve service') ){
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

        $this->mergeIfMissing( [ 'active' => 0 ] );
    }

    public function rules(){
        return [
            'approved'      => [ 'sometimes', 'in:0,1' ],
            'notes'         => [ 'sometimes' ],
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
        return 'api/service.update.failed';
    }
}
