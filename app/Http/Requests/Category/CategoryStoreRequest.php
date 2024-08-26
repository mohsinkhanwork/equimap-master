<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\File;

class CategoryStoreRequest extends BaseRequest{
    public function rules(){
        return [
            'name'  => [ 'required', 'string', 'min: 4', 'unique:categories,name' ],
            'sort'  => [ 'numeric', 'gte:0' ],
            'icon'  => [ 'sometimes', File::image()->max(2000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/category.store.failed';
    }
}
