<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\BaseRequest;
use App\Models\Service;
use Illuminate\Validation\Rule;

class ServiceHorseStoreRequest extends BaseRequest{
    public function prepareForValidation(){

    }

    public function rules(){
        return [
            'horse.*'       => [ 'required', 'exists:horses,id' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/service.horses.created.failed';
    }
}
