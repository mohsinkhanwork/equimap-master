<?php

namespace App\Http\Requests\Trainer;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class TrainerUpdateRequest extends BaseRequest{
    public function rules(){
        // get trainer id that we are updating and merge with request
        $trainer_id           = $this->route('trainer_id');
        if( $trainer_id > 0 ){
            $this->mergeIfMissing( [ 'trainer_id' => $trainer_id ] );
        }

        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('trainers')
                                    ->ignore( $trainer_id )
                                    ->where('provider_id', $this->request->get('provider_id'));

        // active check
        $this->mergeIfMissing( ['active' => $this->request->has('active') ? 1 : 0 ] );

        return [
            'active'        => [ 'required', 'in:1,0' ],
            'trainer_id'    => [ 'required', 'exists:horses,id' ],
            'provider_id'   => [ 'required', 'exists:providers,id' ],
            'name'          => [ 'required', 'string', 'min: 4', $uniqueRule ],
            'phone'         => [ 'required', 'numeric' ],
        ];
    }

    protected function getResponseMessage(){
        return 'api/trainer.update.failed';
    }
}
