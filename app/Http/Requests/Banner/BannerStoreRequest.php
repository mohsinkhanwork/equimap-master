<?php

namespace App\Http\Requests\Banner;

use App\Http\Requests\BaseRequest;
use App\Models\Banner;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class BannerStoreRequest extends BaseRequest{
    public function prepareForValidation(){
        if( $this->has('type') && $this->type == 'app' && $this->has('link_app') ){
            $this->merge( [ 'link' => $this->link_app ] );
        }
        elseif( $this->has('type') && $this->type == 'web' && $this->has('link_web') ){
            $this->merge( [ 'link' => $this->link_web ] );
        }

        if( $this->has('params') && $this->params == '' ){
            $this->request->remove('params');
        }
    }

    public function rules(){
        return [
            'active'    => [ 'in:0,1' ],
            'sort'      => [ 'sometimes', 'numeric', 'gte:0' ],
            'name'      => [ 'required', 'string', 'max:100' ],
            'type'      => [ 'required', Rule::in( array_keys( Banner::getTypes() ) ) ],
            'link'      => [ 'required_unless:type,none', 'string' ],
            'params'    => [ 'sometimes', 'string' ],
            'image'     => [ 'required', 'image', File::image()->max(2000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/banner.create.failed';
    }
}
