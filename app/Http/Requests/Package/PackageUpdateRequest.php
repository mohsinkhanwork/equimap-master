<?php

namespace App\Http\Requests\Package;

use App\Http\Requests\BaseRequest;
use App\Models\Package;
use Illuminate\Validation\Rule;

class PackageUpdateRequest extends BaseRequest{
    public function prepareForValidation(){
        if( auth()->guard('web')->user()->cannot('approve package') ){
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
            'approved'          => [ 'required', 'in:0,1' ],
            'active'            => [ 'required', 'in:0,1' ],
            'sort'              => [ 'sometimes', 'numeric', 'gte:0'],
            'name'              => [ 'sometimes' ],
            'price'             => [ 'sometimes', 'numeric', 'min:1,max:10000' ],
            'quantity'          => [ 'sometimes', 'numeric', 'min:2,max:50' ],
            'notes'             => [ 'sometimes', 'string', 'nullable' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/package.update.failed';
    }
}
