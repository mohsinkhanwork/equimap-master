<?php

namespace App\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidatePermission {
    public static function authorize( $model, $permission=null, $message=null, $guard='web' ){
        $permission = $permission ? $permission : request()->route()->getActionMethod();
        $message    = $message ? $message : __('acp/error.message.forbidden');
        $code       = Response::HTTP_FORBIDDEN;

        if( !auth()->guard( $guard )->check()
            || auth()->guard( $guard )->user()->cannot( $permission, $model ) ){

            if( request()->expectsJson() )
                throw new AuthorizationException( $message, $code );
            else
                throw new HttpException( $code, $message );
        }
    }

    public static function authorizeWeb( $model, $permission=null, $message=null ){
        ValidatePermission::authorize( $model, $permission, $message, 'web' );
    }

    public static function authorizeApi( $model, $permission=null, $message=null ){
        ValidatePermission::authorize( $model, $permission, $message, 'api' );
    }
}
