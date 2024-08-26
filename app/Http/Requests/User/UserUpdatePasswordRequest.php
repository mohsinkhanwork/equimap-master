<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Rules\FbaTokenRule;
use Illuminate\Validation\Rules\Password;

/**
 * @property mixed $token
 * @property mixed $password
 */

class UserUpdatePasswordRequest extends BaseRequest{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(){
        return [
            'token'     => new FbaTokenRule,
            'password'  => [ 'required', 'string', 'confirmed', Password::min(8)->mixedCase() ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/user.reset.failed';
    }
}
