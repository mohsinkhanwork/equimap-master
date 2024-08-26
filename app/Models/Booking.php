<?php

namespace App\Models;

use App\Collections\BookingCollection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @property mixed $status
 */

class Booking extends Model {
    const STATUS_PENDING    = 'pending';
    const BOOKING_TYPES     = [
        'service'   => 'App\Models\Service',
        'trip'      => 'App\Models\Trip',
        'package'   => 'App\Models\Package',
    ];
    const BOOKING_STATUSES  = [
        'scheduled',
        'cancelled',
        'completed',
        'refunded',
        'pending'
    ];

    protected $fillable     = [
        'reference',
        'user_id',
        'parent_id',
        'bookable_id',
        'bookable_type',
        'horse_id',
        'trainer_id',
        'start_time',
        'end_time',
        'status',
        'notes'
    ];
    protected $appends      = [ 'paid', 'rescheduleable', 'rescheduleable_until', 'cancelable' ];
    protected $casts        = [
        'created_at'    => 'date:d-m-Y H:i:s',
        'updated_at'    => 'date:d-m-Y H:i:s',
        'start_time'    => 'date:d-m-Y H:i:s',
        'end_time'      => 'date:d-m-Y H:i:s',
    ];
    protected $hidden       = [
        'bookable_type'
    ];

    protected static function booted(){
        parent::boot();

        // add child bookings if needed
        static::created( function( $booking ){
            $bookable   = $booking->bookable()->first();
            if( utils()->getMorphableName( $bookable->getMorphClass() ) == 'package' && $booking->parent_id == null ){
                $quantity   = $bookable->quantity;
                if( $quantity > 0 && $quantity <= 50 ){
                    for( $i=0; $i < $quantity; $i++ ){
                        $cloned = $booking->replicate()->fill([
                            'bookable_type' => $bookable->packageable_type,
                            'bookable_id'   => $bookable->packageable_id,
                            'parent_id'     => $booking->id,
                            'status'        => 'pending',
                            'start_time'    => Carbon::now()->addDays(90),
                            'end_time'      => Carbon::now()->addDays(90)
                        ]);
                        $cloned->save();
                    }
                }
            }
        });

        static::addGlobalScope('user_id', function ( Builder $builder ) {
            // limit data if provider
            if( utils()->isProvider() ){
                /**
                 * This works here because service already has global scope
                 * to remove all services not belonging to current provider.
                 */
                $builder->whereHas( 'bookable' );
            }
        });
    }

    public static function getBookingTypes(){
        return self::BOOKING_TYPES;
    }

    public static function getBookingStatuses(){
        return self::BOOKING_STATUSES;
    }

    public static function getPendingStatus(){
        return self::STATUS_PENDING;
    }

    public function newCollection( array $models = [] ){
        return new BookingCollection( $models );
    }

    public function isPackage(){
        return $this->getBookingType() == 'package';
    }

    public function isTrip(){
        return $this->getBookingType() == 'trip';
    }

    public function isService(){
        return $this->getBookingType() == 'service';
    }

    public function getBookingType(){
        return Str::lower( Str::replace( "App\Models\\", "", $this->bookable_type ) );
    }

    public function getPaidAttribute(){
        return $this->transactions()->sum('amount')*1;
    }

    public function getRescheduleableAttribute(){
        return in_array( $this->status, config('api.reschedule_status') ) && $this->timeToBooking() > config('api.reschedule_time');
    }

    public function getRescheduleableUntilAttribute(){
        return Carbon::parse( $this->start_time )->subtract(config('api.reschedule_time') . ' hours')->format('Y-m-d H:i:s');
    }

    public function getCancelableAttribute(){
        return in_array( $this->status, config('api.cancel_status') ) && $this->timeToBooking() > config('api.cancel_time');
    }

    public function timeToBooking(){
        $date = Carbon::parse( $this->start_time );

        return $date->isFuture() ? $date->diffInHours() : 0;
    }

    public function scopeWithoutPackages( $query ){
        return $query->where( 'bookable_type', '!=', 'App\Models\Package');
    }

    public function scopeReference( $query, $reference ){
        return $query->where( 'reference', $reference );
    }

    public function scopeDateTime( $query, $startTime, $endTime ){
        if( $startTime && $endTime ){
            return $query
                ->where( 'start_time', '>=', $startTime )
                ->where( 'end_time', '<=', $endTime );
        }

        return $query;
    }

    public function scopeCombined( $query, $service_id=null, $horse_id=null, $trainer_id=null ){
        return $query->where( function( $query ) use ( $service_id, $horse_id, $trainer_id ) {
            if( !is_null( $service_id ) ){
                $query  = $query->orService( $service_id );
            }

            if( !is_null( $horse_id ) ){
                $query  = $query->orHorse( $horse_id );
            }

            if( !is_null( $trainer_id ) ){
                $query  = $query->orTrainer( $trainer_id );
            }

            return $query;
        });
    }

    public function scopeOrService( $query, $service_id ){
        return $query->orWhere( function( $query ) use ($service_id){
            return $query->where('bookable_id', $service_id );
        });
    }

    public function scopeOrHorse( $query, $horse_id ){
        return $horse_id > 0 ? $query->orWhere( function( $query ) use ($horse_id){
            return $query->where( 'horse_id', $horse_id );
        }) : $query;
    }

    public function scopeOrTrainer( $query, $trainer_id ){
        return $trainer_id > 0 ? $query->orWhere( function( $query ) use ($trainer_id){
            return $query->where( 'trainer_id', $trainer_id );
        }) : $query;
    }

    public function scopeSelf( $query ){
        return $query->where( 'user_id', auth()->id() );
    }

    public function addTransaction( $transactionData ){
        return $this->transactions()->create( $transactionData );
    }

    public function getBookable(){
        $bookable    = $this->bookable()->get();
        return $bookable->isNotEmpty() ? $bookable->first() : false;
    }

    public function getHorse(){
        $horse      = $this->horse()->get();
        return $horse->isNotEmpty() ? $horse->first() : false;
    }

    public function getTrainer(){
        $trainer    = $this->trainer()->get();
        return $trainer->isNotEmpty() ? $trainer->first() : false;
    }

    public function bookable(){
        return $this->morphTo();
    }

    public function horse(){
        return $this->belongsTo( 'App\Models\Horse', 'horse_id', 'id' );
    }

    public function trainer(){
        return $this->belongsTo( 'App\Models\Trainer', 'trainer_id', 'id' );
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function transactions(){
        return $this->hasMany('App\Models\Transaction', 'booking_id', 'id');
    }

    public function children(){
        return $this->hasMany('App\Models\Booking', 'parent_id', 'id' );
    }

    public function parent(){
        return $this->hasOne('App\Models\Booking', 'id', 'parent_id' );
    }
}
