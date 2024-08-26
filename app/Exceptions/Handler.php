<?php

namespace App\Exceptions;

use App\Helpers\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use function Nette\Utils\fromReflectionType;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(){

    }

    public function render( $request, Throwable $exception ){
        $class = get_class( $exception );
        if( in_array( $class, [
            'Laravel\Sanctum\Exceptions\MissingAbilityException',
            'Illuminate\Auth\Access\AuthorizationException'] ) && $request->expectsJson() ){
            // return error when user has valid token but route is not authorized for the role
            return utils()->response()
                    ->status( 'forbidden' )
                    ->submit( 'api/general.unauthorized' );
        }
        elseif( $class == 'Illuminate\Auth\AuthenticationException' && $request->expectsJson() ){
            // return error when user doesn't have a valid token
            return utils()->response()
                ->status( 'unauthenticated' )
                ->submit( 'api/general.unauthenticated' );
        }
        elseif( in_array( $class, [
            'Symfony\Component\HttpFoundation\Exception\BadRequestException'
        ]) ){
            return utils()->response()
                ->status( 'error' )
                ->submit( $exception->getMessage(), [] );
        }

        return parent::render( $request, $exception );
    }
}
