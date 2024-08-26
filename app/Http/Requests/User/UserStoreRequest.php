<?php

namespace App\Http\Requests\User;

use Spatie\Permission\Models\Role;
use App\Actions\CastAttributes;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends BaseRequest {
    public function prepareForValidation(){
        // if we can allow user to update users verification status
        if( auth()->check() && auth()->guard('web')->user()->can('verify user') && $this->has('login_verified') ){
            $this->mergeIfMissing([
                'login_verified_at' => utils()->currentTime()
            ]);
        }

        // cast phone number
        if( $this->has('country') && $this->has('login') ){
            $this->merge([
                'login' => CastAttributes::phoneNumber( $this->login, $this->country )
            ]);
        }

        // append roles
        if( !$this->has('roles') ){
            $this->mergeIfMissing([
                'roles'  => [ 'customer' ]
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
            'active'            => [ 'sometimes' ],
            'name'              => [ 'required', 'min:3' ],
            'country'           => [ 'required', 'exists:countries,code' ],
            'login'             => [ 'required', 'unique:users,login', 'phone:country' ],
            'password'          => [ 'required', 'confirmed', Password::min(8)->mixedCase() ],
            'login_verified_at' => [ 'sometimes' ],
            'roles'             => [ 'required', 'array', Rule::In( Role::all()->pluck('name') ) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/user.create.failed';
    }
}
