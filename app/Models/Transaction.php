<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;

class Transaction extends Model {
    public $timestamps      = true;
    protected $fillable     = [
        'code',
        'status',
        'booking_id',
        'user_id',
        'processor',
        'amount',
        'tax',
        'commission',
        'settled',
        'currency',
        'metadata'
    ];

    protected $hidden   = [
        'commission',
        'processor',
        'settled',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function booking(){
        return $this->belongsTo( 'App\Models\Booking', 'booking_id', 'id' );
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
