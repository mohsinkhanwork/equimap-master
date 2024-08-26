<?php

namespace App\Http\Requests\Horse;

use App\Http\Requests\BaseRequest;
use App\Models\Horse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class HorseUpdateRequest extends BaseRequest{
    public function rules(){
        // get horse id that we are updating and merge with request
        $horse_id           = $this->route('horse_id');
        if( $horse_id > 0 ){
            $this->mergeIfMissing( [ 'horse_id' => $horse_id ] );
        }

        // active check
        $this->mergeIfMissing( ['active' => $this->request->has('active') ? 1 : 0 ] );

        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('horses')
                                    ->ignore( $horse_id )
                                    ->where('provider_id', $this->request->get('provider_id'));

        return [
            'active'        => [ 'required', 'in:0,1' ],
            'horse_id'      => [ 'required', 'exists:horses,id' ],
            'provider_id'   => [ 'required', 'exists:providers,id' ],
            'name'          => [ 'required', 'string', 'min: 4', $uniqueRule ],
            'gender'        => [ 'required', 'string', Rule::in( array_keys( Horse::HORSE_GENDER ) ) ],
            'level'         => [ 'required', 'string', Rule::in( array_keys(Horse::HORSE_LEVELS ) ) ],
            'image'         => [ 'sometimes', File::image()->max(2000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/horse.update.failed';
    }
}
