<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class PageUpdateRequest extends BaseRequest{
    public function rules(){
        $page_id    = $this->route('page_id');
        if( $page_id > 0 ){
            $this->mergeIfMissing([
                'page_id'   => $page_id,
                'active'    => $this->has('active') ? 1 : 0
            ]);
        }

        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('pages', 'slug')->ignore( $page_id, 'id' );

        return [
            'active'    => [ 'in:0,1' ],
            'name'      => [ 'required', 'string', 'max:100' ],
            'slug'      => [ 'required', $uniqueRule ],
            'content'   => [ 'required' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/page.update.failed';
    }
}
