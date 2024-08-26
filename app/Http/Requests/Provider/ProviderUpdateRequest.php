<?php

namespace App\Http\Requests\Provider;

use App\Http\Requests\BaseRequest;
use Axiom\Rules\LocationCoordinates;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;


/**
 * @property mixed facilities
 */

class ProviderUpdateRequest extends BaseRequest{
    protected function prepareForValidation(){
        if( $this->has('geo_loc') && $this->geo_loc != '' ){
            list( $lat, $lng )  = explode( ',', $this->geo_loc );
            $address            = utils()->getAddressByCoords( $lat, $lng, true );
            if( !empty( $address ) ){
                $this->merge( $address );
            }
        }

        if( auth()->check() && auth()->guard('web')->user()->can('edit provider featured') ){
            $featured       = $this->has('featured') ? 1 : 0;
            $featuredRanking= $this->has('featured_ranking') ? $this->featured_ranking : 0;
            $this->merge( [ 'featured' => $featured, 'featured_ranking' => $featuredRanking ] );
        }
    }

    public function rules(){
        // get provider id that we are updating and merge with request
        $provider_id    = $this->route('provider_id');
        if( $provider_id > 0 ){
            $this->mergeIfMissing( [ 'provider_id' => $provider_id ] );
        }

        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('providers')->ignore( $provider_id );

        return [
            'name'              => [ 'required', $uniqueRule ],
            'featured'          => [ 'sometimes', 'in:0,1'],
            'featured_ranking'  => [ 'sometimes', 'numeric', 'gte:0' ],
            'address'           => [ 'required', 'min:5' ],
            'description'       => [ 'required', 'min: 25' ],
            'geo_loc'           => [ 'required', new LocationCoordinates ],
            'city'              => [ 'required' ],
            'country'           => [ 'required' ],
            'facilities'        => [ 'required' ],
            'cover'             => [ 'sometimes', File::image()->max(5000) ],
            'gallery.*'         => [ 'sometimes', File::image()->max(5000) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/providers.update.failed';
    }
}
