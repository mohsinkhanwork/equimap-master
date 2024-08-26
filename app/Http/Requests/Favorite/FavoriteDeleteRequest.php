<?php

namespace App\Http\Requests\Favorite;

use App\Http\Requests\BaseRequest;

/**
 * @property mixed $favorite_id
 */

class FavoriteDeleteRequest extends BaseRequest{
    protected function prepareForValidation(){
        $this->mergeIfMissing([
            'id' => $this->favorite_id
        ]);
    }

    public function rules(){
        return [
            'id'    => [ 'required', 'exists:favorites,id,user_id,' . utils()->getUserId() ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/favorite.delete.failed';
    }
}
