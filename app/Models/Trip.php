<?php

namespace App\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;

class Trip extends Model{
    const TRIP_TYPES     = [
        'competition'       => "Competition",
        'travel'            => "Travel"
    ];

    protected $fillable     = [
        'active',
        'approved',
        'sort',
        'name',
        'slug',
        'description',
        'type',
        'included_items',
        'excluded_items',
        'price',
        'currency',
        'origin_country_id',
        'destination_country_id',
        'start_date',
        'end_date',
        'capacity',
        'category_id',
        'provider_id',
        'notes'
    ];
    protected $attributes   = [
        'active'    => 1,
        'type'      => 'travel'
    ];
    protected $casts        = [
        'start_date'    => 'date:Y-m-d',
        'end_date'      => 'date:Y-m-d',
    ];
    protected $appends      = [
        'provider_name', 'days', 'dates', 'share_link'
    ];
    protected $with         = [ 'cover', 'origin', 'destination' ];

    protected static function booted(){
        parent::boot();
        static::creating( function( $trip ){
            $provider = Provider::find( $trip->provider_id );
            $trip->currency = $provider->currency;

            $trip->slug = utils()->slug( $trip->name );
        });

        static::updating( function( $trip ){
            if( isset( $trip->name ) ){
                $trip->slug = utils()->slug( $trip->name );
            }
        });

        static::addGlobalScope('provider', function ( Builder $builder ) {
            if( utils()->isProvider() ){
                $builder->whereHas( 'provider' );
            }
        });
    }

    public static function getTripTypes(){
        return self::TRIP_TYPES;
    }

    public function getProviderNameAttribute(){
        return $this->provider()->get()->first()->name;
    }

    public function getShareLinkAttribute(){
        return route('web.trips.show', [
            'trip_slug' => $this->slug,
            'trip_id'   => $this->id
        ]);
    }

    public function getDaysAttribute(){
        return $this->start_date->diffInDays( $this->end_date ) + 1;
    }

    public function getDatesAttribute(){
        return CarbonPeriod::create( $this->start_date, $this->end_date )->toArray();
    }

    public function uploadImage( $file, $type='gallery' ){
        return ( new Image )->upload( $file, $this, 'gallery' );
    }

    public function scopeActive( $query ){
        return $query->where([
            'active'    => 1,
            'approved'  => 1
        ]);
    }

    public function scopeType( $query, $type ){
        return $query->where( 'type', $type );
    }

    public function provider(){
        return $this->belongsTo( 'App\Models\Provider', 'provider_id', 'id' );
    }

    public function category(){
        return $this->belongsTo( 'App\Models\Category', 'category_id', 'id');
    }

    public function packages(){
        return $this->morphMany('App\Models\Package', 'packageable' );
    }

    public function origin(){
        return $this->belongsTo( 'App\Models\Country', 'origin_country_id', 'id');
    }

    public function destination(){
        return $this->belongsTo( 'App\Models\Country', 'destination_country_id', 'id');
    }

    public function itinerary(){
        return $this->hasMany('App\Models\TripsItinerary', 'trip_id', 'id' );
    }

    public function cover(){
        return $this->morphOne('App\Models\Image', 'imageable' )->where('type', 'cover');
    }

    public function images(){
        return $this->morphMany('App\Models\Image', 'imageable' );
    }
}
