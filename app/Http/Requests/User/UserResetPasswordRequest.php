<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UserResetPasswordRequest extends BaseRequest{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(){
        return [
            'login'     => [ 'required', 'exists:users,login' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/user.reset.failed';
    }
}
