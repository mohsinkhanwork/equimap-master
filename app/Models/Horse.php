<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Horse extends Model{
    use HasFactory;

    const HORSE_LEVELS      = [
        'beginner'          => 'Beginner',
        'intermediate'      => 'Intermediate',
        'advanced'          => 'Advanced'
    ];

    const HORSE_GENDER      = [
        'stallion'          => 'Stallion',
        'mare'              => 'Mare',
        'gelding'           => 'Gelding'
    ];

    public $timestamps      = false;
    protected $fillable     = [
        'active',
        'name',
        'provider_id',
        'gender',
        'level'
    ];
    protected $appends      = [ 'provider_name' ];
    protected $attributes   = [
        'active' => 1
    ];
    protected $with         = [ 'image' ];

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

    public static function getLevels(){
        return self::HORSE_LEVELS;
    }

    public static function getGenders(){
        return self::HORSE_GENDER;
    }

    public function uploadImage( $file ){
        // fetch old icon and upload new
        $oldImage    = $this->image()->get();
        $newImage    = ( new Image )->upload( $file, $this, 'gallery' );

        // delete old icon
        if( $newImage->id > 0 && $oldImage->isNotEmpty() ){
            $oldImage->first()->delete();
        }
    }
    ### RESPONSIBILITY METHODS (END) ###


    ### RELATIONS (BEGIN) ###
    public function provider(){
        return $this->belongsTo( 'App\Models\Provider', 'provider_id', 'id' );
    }

    public function schedules(){
        return $this->morphMany('App\Models\Schedule', 'scheduleable' );
    }

    public function calendars(){
        return $this->morphMany('App\Models\Calendar', 'calendarable' );
    }

    public function image(){
        return $this->morphOne('App\Models\Image', 'imageable');
    }
    ### RELATIONS (END) ###
}
