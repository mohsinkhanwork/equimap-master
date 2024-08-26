<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\BaseRequest;

class PageStoreRequest extends BaseRequest{
    public function rules(){
        return [
            'active'    => [ 'in:0,1' ],
            'name'      => [ 'required', 'string', 'max:100' ],
            'slug'      => [ 'required', 'unique:pages,slug' ],
            'content'   => [ 'required' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/page.create.failed';
    }
}
