<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Trainer extends Model{
    use HasFactory;

    public $timestamps      = false;
    protected $fillable     = [
        'active',
        'name',
        'phone',
        'provider_id'
    ];
    protected $appends      = [ 'provider_name' ];
    protected $attributes   = [
        'active' => 1
    ];

    protected static function booted(){
        parent::boot();
        static::addGlobalScope('provider', function ( Builder $builder ) {
            if( utils()->isProvider() ){
                $builder->whereHas( 'provider' );
            }
        });
    }

    public function getProviderNameAttribute(){
        return $this->provider()->get()->first()->name;
    }

    /**
     * @param $request Request;
     */
    public function scheduleExists( $request ){
        $schedules =  $this
            ->schedules()
            ->get();

        if( $schedules->isNotEmpty() ){
            $schedules   = $schedules->filterByParams( $request->only( [ 'day', 'start_time', 'end_time'] ) );
            return $schedules->isNotEmpty();
        }
    }

    public function provider(){
        return $this->belongsTo( 'App\Models\Provider', 'provider_id', 'id' );
    }

    public function schedules(){
        return $this->morphMany('App\Models\Schedule', 'scheduleable' );
    }

    public function calendars(){
        return $this->morphMany('App\Models\Calendar', 'calendarable' );
    }
}
