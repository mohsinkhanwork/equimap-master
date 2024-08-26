<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class CategoryUpdateRequest extends BaseRequest{
    public function rules(){
        // get category id that we are updating and merge with request
        $category_id    = $this->route('category_id');
        if( $category_id > 0 ){
            $this->mergeIfMissing( [ 'category_id' => $category_id ] );
        }

        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('categories', 'name')->ignore( $category_id );

        return [
            'name'  => [ 'required', 'string', 'min: 4', $uniqueRule ],
            'sort'  => [ 'numeric', 'gte:0' ],
            'icon'  => [ 'sometimes', File::image()->max(2000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/category.update.failed';
    }
}
