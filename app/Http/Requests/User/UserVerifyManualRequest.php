<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UserVerifyManualRequest extends BaseRequest {
    public function prepareForValidation(){
        $user_id    = $this->route('user_id');
        if( $user_id > 0 ){
            $this->mergeIfMissing( [ 'user_id' => $user_id ] );
        }
    }

    public function rules(){
        return [
            'user_id'   => [ 'required', Rule::exists('users', 'id' )->whereNull('login_verified_at') ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/user.verification.failed';
    }
}
