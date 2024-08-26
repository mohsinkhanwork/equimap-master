<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use function Illuminate\Session\userId;

class Provider extends Model {
    use HasFactory;

    public $timestamps      = true;
    protected $with         = [ 'cover' ];
    protected $fillable     = [
        'name',
        'slug',
        'featured',
        'featured_ranking',
        'address',
        'geo_loc',
        'city',
        'country',
        'currency',
        'description',
        'user_id'
    ];
    protected $appends      = [ 'summary', 'packages', 'in_favorites', 'share_link' ];

    protected static function booted(){
        parent::boot();

        static::addGlobalScope('user_id', function ( Builder $builder ) {
            if( utils()->isProvider() ){
                $builder->where( 'user_id', auth()->guard('web')->id() );
            }
        });

        static::creating( function( $provider ){
            $provider->user_id  = auth()->id();
            $provider->slug     = utils()->slug( $provider->name );

            // get currency code from country
            $country            = Country::find($provider->country);
            if( !empty( $country ) ){
                $provider->currency = $country->currency;
            }
        });

        static::updating( function( $provider ){
            $provider->slug     = utils()->slug( $provider->name );
        });
    }

    public function getShareLinkAttribute(){
        return route('web.providers.show', [
            'provider_slug' => $this->slug,
            'provider_id'   => $this->id
        ]);
    }

    public function getPackagesAttribute(){
        $services   = $this->servicePackages()->active()->get();
        $trips      = $this->tripPackages()->active()->get();

        return $services->merge( $trips );
    }

    public function getSummaryAttribute(){
        return [
            'available_services'    => $this->services(false)->count(),
            'available_trips'       => $this->trips(false)->count(),
            'available_horses'      => $this->horses(false)->count(),
            'available_trainers'    => $this->trainers(false)->count(),
            'lowest_price'          => $this->services()->min('price'),
            'highest_price'         => $this->services()->max('price')
        ];
    }

    public function getInFavoritesAttribute(){
        $wishlist   = $this->favorites()->where('user_id', utils()->getUserId() )->get();
        return $wishlist->isNotEmpty() ? $wishlist->first()->id : null;
    }

    public function uploadCover( $file, $type='cover' ){
        // fetch old icon and upload new
        $oldCover   = $this->cover()->get();
        $newCover   = ( new Image )->upload( $file, $this, 'cover' );

        // delete old icon
        if( $newCover->id > 0 && $oldCover->isNotEmpty() ){
            $oldCover->first()->delete();
        }
    }

    public function uploadImage( $file, $type='gallery' ){
        return ( new Image )->upload( $file, $this, 'gallery' );
    }

    public function cover(){
        return $this->morphOne('App\Models\Image', 'imageable' )->where('type', 'cover');
    }

    public function images(){
        return $this->morphMany('App\Models\Image', 'imageable' )->where('type', 'gallery');
    }

    public function services(){
        return $this->hasMany( 'App\Models\Service', 'provider_id', 'id' );
    }

    public function trips(){
        return $this->hasMany( 'App\Models\Trip', 'provider_id', 'id' );
    }

    public function servicePackages(){
        return $this->hasManyThrough(
            'App\Models\Package',
            'App\Models\Service',
            'provider_id',
            'packageable_id',
            'id',
            'id'
        )->where('packageable_type', 'App\Models\Service');
    }

    public function tripPackages(){
        return $this->hasManyThrough(
            'App\Models\Package',
            'App\Models\Trip',
            'provider_id',
            'packageable_id',
            'id',
            'id'
        )->where('packageable_type', 'App\Models\Trip');
    }

    public function courses(){
        return $this->hasMany( 'App\Models\Course', 'provider_id', 'id' );
    }

    public function horses( $withScope=true ){
        return $withScope
                    ? $this->hasMany( 'App\Models\Horse', 'provider_id', 'id' )
                    : $this->hasMany( 'App\Models\Horse', 'provider_id', 'id' )->withoutGlobalScopes();
    }

    public function trainers( $withScope=true ){
        return $withScope
                    ? $this->hasMany( 'App\Models\Trainer', 'provider_id', 'id' )
                    : $this->hasMany( 'App\Models\Trainer', 'provider_id', 'id' )->withoutGlobalScopes();
    }

    public function user(){
        return $this->hasOne( 'App\Models\User', 'id', 'user_id');
    }

    public function favorites(){
        return $this->hasOne('App\Models\Favorite', 'provider_id', 'id' );
    }

    public function provider_facilities(){
        return $this->belongsToMany('App\Models\ProvidersFacility',
            \App\Models\ProvidersFacility::class,
            'provider_id',
            'facility_id'
        );
    }

    public function facilities(){
        return $this->hasManyThrough(
            'App\Models\Facility',
            'App\Models\ProvidersFacility',
            'provider_id',
            'id',
            'id',
            'facility_id'
        )
            ->select( ['facilities.id','name','slug'] )
            ->with( ['icon'] )
            ->orderByDesc( 'sort' );
    }
}
