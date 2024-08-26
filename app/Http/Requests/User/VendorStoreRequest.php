<?php

namespace App\Http\Requests\User;

use App\Actions\CastAttributes;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Password;

class VendorStoreRequest extends BaseRequest {
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
            'name'          => [ 'required', 'min:3' ],
            'login'         => [ 'required', 'phone:country', 'unique:users,login', ],
            'country'       => [ 'required_with:country' ],
            'password'      => [ 'required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols() ],
            'agree_terms'   => [ 'required', 'in:1' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/user.register.failed';
    }
}
