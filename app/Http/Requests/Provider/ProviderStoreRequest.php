<?php

namespace App\Http\Requests\Provider;

use App\Http\Requests\BaseRequest;
use Axiom\Rules\LocationCoordinates;
use Illuminate\Validation\Rules\File;


/**
 * @property mixed facilities
 */

class ProviderStoreRequest extends BaseRequest{
    protected function prepareForValidation(){
        if( $this->has('geo_loc') && $this->geo_loc != '' ){
            list( $lat, $lng )  = explode( ',', $this->geo_loc );
            $address            = utils()->getAddressByCoords( $lat, $lng, true );
            if( !empty( $address ) ){
                $this->merge( $address );
            }
        }

        if( $this->has('featured') && auth()->guard('web')->user()->cannot('edit provider featured') ){
            $this->merge( [ 'featured' => 0, 'featured_ranking' => 0 ] );
        }
    }

    public function rules(){
        return [
            'name'              => [ 'required', 'unique:providers,name' ],
            'featured'          => [ 'sometimes', 'in:0,1'],
            'featured_ranking'  => [ 'sometimes', 'numeric', 'gte:0' ],
            'address'           => [ 'required', 'min:5' ],
            'description'       => [ 'required', 'min: 25' ],
            'geo_loc'           => [ 'required', new LocationCoordinates ],
            'city'              => [ 'required' ],
            'country'           => [ 'required' ],
            'facilities'        => [ 'required' ],
            'cover'             => [ 'required', File::image()->max(5000) ],
            'gallery.*'         => [ 'sometimes', File::image()->max(5000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/providers.create.failed';
    }
}
