<?php

namespace App\Http\Requests\Favorite;

use App\Http\Requests\BaseRequest;

/**
 * @property int $provider_id
 */

class FavoriteStoreRequest extends BaseRequest{
    protected function prepareForValidation(){
        $this->merge([
            'provider_id'   => filter_var( $this->provider_id, FILTER_SANITIZE_NUMBER_INT ),
        ]);
    }

    public function rules(){
        return [
            'provider_id'   => [ 'required', 'exists:providers,id' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/favorite.create.failed';
    }
}
