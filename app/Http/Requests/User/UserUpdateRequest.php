<?php

namespace App\Http\Requests\User;

use App\Actions\CastAttributes;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserUpdateRequest extends BaseRequest {
    public function prepareForValidation(){
        // remove null values
        $this->request->replace(
            array_filter( $this->all() )
        );

        // if we can allow user to update users verification status
        if( $this->has('login_verified') && auth()->guard('web')->check() && auth()->guard('web')->user()->can('verify user') ){
            $this->mergeIfMissing([
                'login_verified_at' => $this->has('login_verified') ? utils()->currentTime() : null
            ]);
        }

        // cast phone number
        if( $this->has('country') && $this->has('login') ){
            $this->merge([
                'login' => CastAttributes::phoneNumber( $this->login, $this->country )
            ]);
        }

        // append roles
        if( !$this->has('roles') || empty( $this->roles ) ){
            $this->mergeIfMissing([
                'roles'  => [ 'customer' ]
            ]);
        }

        // set active value
        $this->mergeIfMissing([
            'active'    => $this->has('active') ? 1 : 0
        ]);
    }

    public function rules(){
        return [
            'active'            => [ 'sometimes' ],
            'name'              => [ 'sometimes', 'min:3' ],
            'password'          => [ 'sometimes', 'confirmed', Password::min(8)->mixedCase() ],
            'roles'             => [ 'sometimes', 'array', Rule::In( Role::all()->pluck('name') ) ],
            'login_verified_at' => [ 'sometimes']
        ];
    }

    protected function getResponseMessage(){
        return 'api/user.update.failed';
    }
}
