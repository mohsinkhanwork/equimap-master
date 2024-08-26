<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Rules\FbaTokenRule;

class UserVerifyRequest extends BaseRequest {
    public function rules(){
        return [
            'token' => new FbaTokenRule
        ];
    }

    protected function getResponseMessage(){
        return 'api/user.verification.failed';
    }
}
