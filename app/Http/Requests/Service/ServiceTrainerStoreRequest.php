<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\BaseRequest;
use App\Models\Service;
use Illuminate\Validation\Rule;

class ServiceTrainerStoreRequest extends BaseRequest{
    public function rules(){
        return [
            'trainer.*' => [ 'required', 'exists:trainers,id' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/service.trainers.create.failed';
    }
}
