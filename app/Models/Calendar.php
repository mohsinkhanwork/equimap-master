<?php

namespace App\Models;

use App\Collections\CalendarCollection;
use Illuminate\Support\Str;

class Calendar extends Model {
    const TYPE_BLOCK    = 'block';
    const TYPE_PRICE    = 'price';

    public $timestamps      = false;
    protected $fillable     = [
        'calendarable_type',
        'calendarable_id',
        'event_type',
        'start_time',
        'end_time',
        'price',
        'notes'
    ];

    protected $hidden       = [
        'calendarable_type',
        'calendarable_id'
    ];

    public function newCollection( array $models = [] ){
        return new CalendarCollection( $models );
    }

    public function scopeDateTime( $query, $startTime, $endTime ){
        if( $startTime && $endTime ){
            return $query
                        ->where( 'start_time', '>=', $startTime )
                        ->where( 'end_time', '<=', $endTime );
        }

        return $query;
    }

    public function scopeCombined( $query, $service_id, $horse_id=0, $trainer_id=0 ){
        return $query->where( function( $query ) use ( $service_id, $horse_id, $trainer_id ) {
            $query  = $query->orService( $service_id );

            if( $horse_id > 0 ){
                $query  = $query->orHorse( $horse_id );
            }

            if( $trainer_id > 0 ){
                $query  = $query->orTrainer( $trainer_id );
            }

            return $query;
        });
    }

    public function scopeOrService( $query, $service_id ){
        return $query->orWhere( function( $query ) use ($service_id){
            return $query->where([
                'calendarable_type' => Service::class,
                'calendarable_id'   => $service_id
            ]);
        });
    }

    public function scopeOrHorse( $query, $horse_id ){
        return $horse_id > 0 ? $query->orWhere( function( $query ) use ($horse_id){
            return $query->where([
                'calendarable_type' => Horse::class,
                'calendarable_id'   => $horse_id
            ]);
        }) : $query;
    }

    public function scopeOrTrainer( $query, $trainer_id ){
        return $trainer_id > 0 ? $query->orWhere( function( $query ) use ($trainer_id){
            return $query->where([
                'calendarable_type' => Trainer::class,
                'calendarable_id'   => $trainer_id
            ]);
        }) : $query;
    }

    public function getCalendarType(){
        return Str::lower( Str::replace( "App\Models\\", "", $this->calendarable_type ) );
    }

    public function calendarable(){
        return $this->morphTo();
    }
}
