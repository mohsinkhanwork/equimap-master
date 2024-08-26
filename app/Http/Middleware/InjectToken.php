<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectToken{
    public function handle( Request $request, Closure $next ){
        if( $request->has('_token') && !$request->headers->has('Authorization') ){
            $request->headers->set( 'Authorization', 'Bearer ' . $request->input('_token') );
        }
        return $next($request);
    }
}
