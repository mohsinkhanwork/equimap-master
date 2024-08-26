<?php

namespace App\Models;

class Category extends Model {
    public $timestamps      = true;
    protected $fillable     = [
        'name',
        'slug',
        'sort'
    ];

    protected $attributes   = [
        'sort' => 0
    ];

    protected $with         = [ 'icon' ];

    protected static function booted(){
        parent::boot();
        static::creating( function( $category ){
            $category->slug = utils()->slug( $category->name );
        });

        static::updating( function( $category ){
            if( isset( $category->name ) ){
                $category->slug = utils()->slug( $category->name );
            }
        });
    }

    public function scopeEnabledServices( $query ){
        return $this->providers()->whereHas('services', function( $query ){
            return $query->where([
                'active'    => 1,
                'approved'  => 1
            ]);
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

    public function services(){
        return $this->hasMany( 'App\Models\Service', 'category_id', 'id' );
    }

    public function providers(){
        return $this->hasManyThrough(
            'App\Models\Provider',
            'App\Models\Service',
            'category_id',
            'id',
            'id',
            'provider_id'
        );
    }

    public function icon(){
        return $this->morphOne('App\Models\Image', 'imageable');
    }
}
