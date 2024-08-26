<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model {
    use HasFactory;

    public $timestamps      = true;
    protected $fillable     = [
        'rate',
        'review',
        'user_id',
        'service_id'
    ];

    protected static function booted(){
        parent::boot();
        static::creating( function( $review ){
            /*
             * @TODO: Make sure user has used service before allowing review.
             */

            $review->user_id  = !isset( $review->user_id ) ? utils()->getUserId() : $review->user_id;
        });
    }

    public function service(){
        return $this->belongsTo( 'App\Models\Service', 'service_id', 'id' );
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
