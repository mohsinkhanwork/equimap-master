<?php

namespace App\Http\Requests\Horse;

use App\Http\Requests\BaseRequest;
use App\Models\Horse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class HorseStoreRequest extends BaseRequest{
    public function rules(){
        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('horses')
                                ->where('provider_id', $this->request->get('provider_id'));

        // active check
        $this->mergeIfMissing( ['active' => $this->request->has('active') ? 1 : 0 ] );

        return [
            'active'        => [ 'required', 'in:0,1' ],
            'name'          => [ 'required', 'string', 'min: 4', $uniqueRule ],
            'provider_id'   => [ 'required', 'exists:providers,id' ],
            'gender'        => [ 'required', 'string', Rule::in( array_keys( Horse::HORSE_GENDER ) ) ],
            'level'         => [ 'required', 'string', Rule::in( array_keys(Horse::HORSE_LEVELS ) ) ],
            'image'         => [ 'sometimes', File::image()->max(2000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/horse.store.failed';
    }
}
