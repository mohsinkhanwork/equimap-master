<?php

namespace App\Http\Requests\Trainer;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class TrainerStoreRequest extends BaseRequest{
    public function rules(){
        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('trainers')
                                ->where('provider_id', $this->request->get('provider_id'));

        // active check
        $this->mergeIfMissing( ['active' => $this->request->has('active') ? 1 : 0 ] );

        return [
            'active'        => [ 'required', 'in:1,0' ],
            'name'          => [ 'required', 'string', 'min: 4', $uniqueRule ],
            'phone'         => [ 'required', 'numeric' ],
            'provider_id'   => [ 'required', 'exists:providers,id' ],
        ];
    }

    protected function getResponseMessage(){
        return 'api/trainer.create.failed';
    }
}
