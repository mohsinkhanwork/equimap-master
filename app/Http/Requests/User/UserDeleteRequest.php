<?php

namespace App\Http\Requests\User;

use App\Actions\CastAttributes;
use App\Http\Requests\BaseRequest;

/**
 * @property mixed login
 * @property mixed password
 */

class UserDeleteRequest extends BaseRequest{
    public function prepareForValidation(){
        if( $this->has('country') && $this->has('login') ){
            $this->merge([
                'login' => CastAttributes::phoneNumber( $this->login, $this->country )
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(){
        return [
            'country'       => [ 'required' ],
            'login'         => [ 'required', 'exists:users,login', 'phone:country' ],
            'password'      => [ 'required' ]
        ];
    }

    protected function getResponseMessage(){
        return 'web/users.delete.failed';
    }
}
