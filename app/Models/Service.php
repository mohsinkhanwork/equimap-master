<?php

namespace App\Models;

use App\Actions\ServiceApprovalStatusAction;
use App\Services\BookingServiceAvailabilityService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property bool $active
 * @property string $name
 * @property string $description
 * @property int $price
 * @property string $unit Defines if property pricing is based on hours, days or other.
 * @property int $capacity Number of guests that can be accommodated.
 * @property string currency Currency of payment for the given service.
 * @property int $category_id Foreign relation (belongs to) to categories table.
 * @property int $provider_id Foreign relation (belongs to) to providers table.
 */

class Service extends Model {
    use HasFactory;

    const SERVICE_UNITS     = [
        'hour'      => 'Hourly',
        'day'       => 'Daily'
    ];

    const SERVICE_DAYS      = [
        'sunday'    => 'Sunday',
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
        'saturday'  => 'Saturday',
    ];

    const SERVICE_RESTRICTED_FIELDS = [
        'name',
        'description',
        'price'
    ];

    const SCHEDULED_STATUS  = 'scheduled';

    public $timestamps      = true;
    protected $fillable     = [
        'active',
        'approved',
        'sort',
        'name',
        'description',
        'price',
        'unit',
        'capacity',
        'currency',
        'provider_id',
        'category_id',
        'notes'
    ];
    protected $appends      = [ 'provider_name' ];

    protected static function booted(){
        parent::boot();
        static::addGlobalScope('provider', function ( Builder $builder ) {
            if( utils()->isProvider() ){
                $builder->whereHas( 'provider' );
            }
        });

        static::creating( function( $service ) {
           $provider = Provider::find( $service->provider_id );
           $service->currency = $provider->currency;
        });

        static::updating( function( $service ){
            if( ServiceApprovalStatusAction::denied( $service ) ){
                $service->approved  = 0;
                $service->notes     = config('general.service.default_notes');
            }
        });
    }

    public function getProviderNameAttribute(){
        return $this->provider()->get()->first()->name;
    }

    public function getCategoryNameAttribute(){
        return $this->category()->get()->isNotEmpty()
                    ? $this->category()->get()->first()->name
                    : 'Un-categorized';
    }

    ### RESPONSIBILITY METHODS (BEGIN) ###
    public static function getServiceUnits(){
        return self::SERVICE_UNITS;
    }

    public static function getServiceDays(){
        return self::SERVICE_DAYS;
    }

    public static function getServiceRestrictedFields(){
        return self::SERVICE_RESTRICTED_FIELDS;
    }

    public static function getScheduledStatus(){
        return self::SCHEDULED_STATUS;
    }

    /**
     * @param $request \Illuminate\Http\Request;
     */
    public function scheduleExists( $request ){
        $schedules =  $this
                        ->schedules()
                        ->active()
                        ->time( $request->input('start_time'), $request->input('end_time') )
                        ->get();

        if( $schedules->isNotEmpty() ){
            return true;
        }
    }

    public function getReviewsSummaryAttribute(){
        $reviews = $this
                    ->reviews()
                    ->selectRaw( 'service_id, avg(rate) as average_rate, count(id) as total_ratings')
                    ->groupBy( 'service_id' )
                    ->get();

        return $reviews->isNotEmpty() ? $reviews->first() : null;
    }
    ### RESPONSIBILITY METHODS (END) ###


    ### RELATIONS (BEGIN) ###
    public function scopeActive( $query ){
        return $query->where([
            'active'    => 1,
            'approved'  => 1
        ]);
    }

    public function provider(){
        return $this->belongsTo( 'App\Models\Provider', 'provider_id', 'id');
    }

    public function category(){
        return $this->belongsTo( 'App\Models\Category', 'category_id', 'id');
    }

    public function reviews(){
        return $this->hasMany('App\Models\Review', 'service_id', 'id' );
    }

    public function schedules(){
        return $this->morphMany('App\Models\Schedule', 'scheduleable' );
    }

    public function packages(){
        return $this->morphMany('App\Models\Package', 'packageable' );
    }

    public function schedulesGrouped(){
        return $this->schedules()->active()->orderBy('start_time')->get()->groupedByDay();
    }

    public function bookings(){
        return $this->hasMany( 'App\Models\Booking', 'service_id', 'id' );
    }

    public function calendars(){
        return $this
                ->morphMany('App\Models\Calendar', 'calendarable' );
    }

    public function service_horses(){
        return $this->belongsToMany( 'App\Models\ServicesHorse',
            \App\Models\ServicesHorse::class,
        'service_id',
        'horse_id' );
    }

    public function service_trainers(){
        return $this->belongsToMany( 'App\Models\ServicesTrainer',
            \App\Models\ServicesTrainer::class,
            'service_id',
            'trainer_id');
    }

    public function horses(){
        return $this->hasManyThrough(
            'App\Models\Horse',
            'App\Models\ServicesHorse',
            'service_id',
            'id',
            'id',
            'horse_id'
        );
    }

    public function trainers(){
        return $this->hasManyThrough(
            'App\Models\Trainer',
            'App\Models\ServicesTrainer',
            'service_id',
            'id',
            'id',
            'trainer_id'
        );
    }
    ### RELATIONS (END) ###
}
