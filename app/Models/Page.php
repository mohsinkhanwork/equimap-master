<?php

namespace App\Models;

class Page extends Model {

    protected $fillable = [
        'active',
        'name',
        'slug',
        'content',
        'user_id'
    ];

    protected static function booted(){
        parent::boot();

        static::creating( function( $page ){
            $page->user_id  = auth()->id();
        });
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
