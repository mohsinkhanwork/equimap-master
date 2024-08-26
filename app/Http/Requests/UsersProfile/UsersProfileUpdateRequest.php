<?php

namespace App\Http\Requests\UsersProfile;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class UsersProfileUpdateRequest extends BaseRequest {
    public function rules(){
        return [
            'name'      => [ 'min: 5' ],
            'gender'    => [ 'in:male,female' ],
            'birthday'  => [ 'date', 'date_format:Y-m-d', 'before:18 years ago' ],
            'language'  => [ 'in:en,ar' ],
            'weight'    => [ 'integer', 'min:20,max:500' ] ,
            'level'     => [ 'in:beginner,intermediate,advanced' ],
            'email'     => [ 'email' ],
            'image'     => [ File::image()->max(2000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/users_profile.update.failed';
    }
}
