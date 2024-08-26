<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;

class UsersDevice extends Model {
    use HasFactory, Authorizable;

    public $timestamps      = true;
    protected $fillable     = [
        'active',
        'token',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }
}
