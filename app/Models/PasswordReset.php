<?php

namespace App\Models;

class PasswordReset extends Model {
    public $timestamps      = true;
    protected $fillable     = [
        'login',
        'token',
        'expires_at'
    ];
}
