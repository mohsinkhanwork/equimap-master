<?php

namespace App\Models;

class Facility extends Model {
    protected $table        = 'facilities';
    protected $fillable     = [
        'name',
        'slug',
        'sort'
    ];
    protected $with         = [ 'icon' ];

    protected static function booted(){
        parent::boot();
        static::creating( function( $facility ){
            $facility->slug = utils()->slug( $facility->name );
        });

        static::updating( function( $facility ){
            if( isset( $facility->name ) ){
                $facility->slug = utils()->slug( $facility->name );
            }
        });
    }

    public function uploadIcon( $file ){
        // fetch old icon and upload new
        $oldIcon    = $this->icon()->get();
        $newIcon    = ( new Image )->upload( $file, $this, 'icon' );

        // delete old icon
        if( $newIcon->id > 0 && $oldIcon->isNotEmpty() ){
            $oldIcon->first()->delete();
        }
    }

    public function icon(){
        return $this->morphOne('App\Models\Image', 'imageable' );
    }
}
