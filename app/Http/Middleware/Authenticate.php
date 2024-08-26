<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends  Middleware
{
    public function redirectTo($request){
        return route('acp.auth.index');
    }
}
