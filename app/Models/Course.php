<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Course extends Model{
    const COURSE_UNITS      = [
        'hour'      => 'Hourly',
        'day'       => 'Daily'
    ];

    const COURSE_PROGRESSION_TYPES     = [
        'random'            => "Random Order",
        'linear'            => "Progressive Order"
    ];

    const COURSE_DAYS      = [
        'sunday'    => 'Sunday',
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
        'saturday'  => 'Saturday',
    ];

    protected $fillable     = [
        'active',
        'approved',
        'sort',
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'unit',
        'progression_type',
        'category_id',
        'provider_id',
        'notes'
    ];
    protected $attributes   = [
        'active'            => 1,
        'unit'              => 'hour',
        'progression_type'  => 'random'
    ];
    protected $appends      = [
        'share_link'
    ];

    protected static function booted(){
        parent::boot();
        static::creating( function( $course ){
            $provider = Provider::find( $course->provider_id );
            $course->currency = $provider->currency;

            $course->slug = utils()->slug( $course->name );
        });

        static::updating( function( $course ){
            if( isset( $course->name ) ){
                $course->slug = utils()->slug( $course->name );
            }
        });

        static::addGlobalScope('provider', function ( Builder $builder ) {
            if( utils()->isProvider() ){
                $builder->whereHas( 'provider' );
            }
        });
    }

    public static function getUnitTypes(){
        return self::COURSE_UNITS;
    }

    public static function getProgressionTypes(){
        return self::COURSE_PROGRESSION_TYPES;
    }

    public static function getCourseDays(){
        return self::COURSE_DAYS;
    }

    public function getShareLinkAttribute(){
        return route('web.course.show', [
            'course_slug' => $this->slug,
            'course_id'   => $this->id
        ]);
    }

    public function scopeActive( $query ){
        return $query->where([
            'active'    => 1,
            'approved'  => 1
        ]);
    }

    public function provider(){
        return $this->belongsTo( 'App\Models\Provider', 'provider_id', 'id' );
    }

    public function category(){
        return $this->belongsTo( 'App\Models\Category', 'category_id', 'id');
    }

    public function classes(){
        return $this->hasMany('App\Models\CoursesClass' );
    }

    public function schedules(){
        return $this->morphMany('App\Models\Schedule', 'scheduleable' );
    }
}
