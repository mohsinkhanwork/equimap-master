<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\File;

/**
 * @property int $category_id
 * @property string $type
 */

class CategoryIconUploadRequest extends BaseRequest{
    protected function prepareForValidation(){
        $this->mergeIfMissing([
            'type'          => 'icon',
            'category_id'   => request()->route('category_id')
        ]);
    }

    public function rules(){
        return [
            'icon'          => [ 'required', File::image()->max(2000) ],
            'type'          => [ 'required', 'in:icon'],
            'category_id'   => [ 'required', 'exists:categories,id']
        ];
    }

    protected function getResponseMessage(){
        return 'api/category.icon.failed';
    }
}
