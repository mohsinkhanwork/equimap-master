<?php

namespace App\Models;

use App\Collections\ScheduleCollection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Schedule extends Model {
    use HasFactory;

    public $timestamps      = false;
    protected $fillable     = [
        'active',
        'day',
        'start_time',
        'end_time',
        'price',
        'scheduleable_type',
        'scheduleable_id'
    ];

    protected $hidden       = [
        'scheduleable_id'
    ];

    protected $casts = [
        'start_time'    => 'datetime:H:i',
        'end_time'      => 'datetime:H:i',
    ];

    public function newCollection( array $models = [] ){
        return ( new ScheduleCollection( $models ) );
    }

    public function getStartTimeAttribute( $value ){
        return $value !== null ? Carbon::parse( $value )->format('H:i') : null;
    }

    public function getEndTimeAttribute( $value ){
        return $value !== null ? Carbon::parse( $value )->format('H:i') : null;
    }

    public function scopeActive( $query ){
        return $query->where('active', 1);
    }

    public function scopeTime( $query, $startTime, $endTime ){
        return $query
            ->whereIn( 'start_time', $startTime )
            ->orWhereIn( 'end_time', $endTime );
    }

    public function scopeDay( $query, $day ){
        return $query->where( 'day', $day );
    }

    public function scopeCombined( $query, $params ){
        return $query->where( function( $query ) use ( $params ) {
            return $query->orService( $params->input('service_id') )
                ->orHorse( $params->input('horse_id') )
                ->orTrainer( $params->input('trainer_id') );
        });
    }

    public function scopeOrService( $query, $service_id ){
        return $query->orWhere( function( $query ) use ($service_id){
            return $query->where([
                'scheduleable_type' => Service::class,
                'scheduleable_id'   => $service_id
            ]);
        });
    }

    public function scopeOrHorse( $query, $horse_id ){
        return $horse_id > 0 ? $query->orWhere( function( $query ) use ($horse_id){
            return $query->where([
                'scheduleable_type' => Horse::class,
                'scheduleable_id'   => $horse_id
            ]);
        }) : $query;
    }

    public function scopeOrTrainer( $query, $trainer_id ){
        return $trainer_id > 0 ? $query->orWhere( function( $query ) use ($trainer_id){
            return $query->where([
                'scheduleable_type' => Trainer::class,
                'scheduleable_id'   => $trainer_id
            ]);
        }) : $query;
    }

    public function getDayAttribute( $value ){
        return $value ? __('api/schedules.days.' . $value ) : null;
    }

    public function getScheduleType(){
        return Str::lower( Str::replace( "App\Models\\", "", $this->scheduleable_type ) );
    }

    public function scheduleable(){
        return $this->morphTo();
    }

    public function calendar(){
        return $this->morphMany( 'App\Models\Calendar', 'calendarable' );
    }
}
