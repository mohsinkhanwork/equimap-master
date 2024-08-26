<?php

namespace App\Models;

/**
 * @property bool $params Additional parameters for link.
 */

class Banner extends Model {
    const BANNER_TYPES      = [
        'none'              => 'None',
        'app'               => 'Mobile App Navigation',
        'web'               => 'Website Link'
    ];

    const BANNER_ENTITIES   = [
        'main'              => 'Homescreen',
        'provider'          => 'Provider',
        'service'           => 'Service',
        'package'           => 'Package',
        'discipline'        => 'Discipline',
        'profile'           => 'Profile',
        'booking_history'   => 'Booking History',
        'favorite'          => 'Favorites',
        'trip'              => 'Trips',
        'shop'              => 'Shop'
    ];

    protected $fillable = [
        'active',
        'sort',
        'name',
        'type',
        'link',
        'params'
    ];

    protected $with     = [ 'image' ];
    protected $appends  = [ 'additional' ];


    public static function getTypes(){
        return self::BANNER_TYPES;
    }

    public static function getEntities(){
        return self::BANNER_ENTITIES;
    }

    public function getAdditionalAttribute(){
        $splitComma = explode( ',', $this->params );
        $parsedParams   = [];
        if( !empty( $splitComma ) ){
            foreach( array_filter( $splitComma, 'strlen' ) as $param ){
                if( !is_numeric( strpos( ':', $param ) ) ){
                    continue;
                }

                list( $key, $value )= explode( ':', $param );
                $parsedParams[$key] = $value;
            }
        }

        return $parsedParams;
    }

    public function uploadImage( $file ){
        // fetch old icon and upload new
        $oldBanner  = $this->image()->get();
        $newBanner  = ( new Image )->upload( $file, $this, 'banner' );

        // delete old icon
        if( $newBanner->id > 0 && $oldBanner->isNotEmpty() ){
            $oldBanner->first()->delete();
        }
    }

    public function image(){
        return $this->morphOne('App\Models\Image', 'imageable');
    }
}
