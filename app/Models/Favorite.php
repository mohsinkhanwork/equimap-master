<?php

namespace App\Models;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'provider_id'
    ];
    protected $with = ['provider'];

    public function provider(){
        return $this->belongsTo('App\Models\Provider', 'provider_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
