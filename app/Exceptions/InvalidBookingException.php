<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InvalidBookingException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void{
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render( Request $request ){
        return $request->expectsJson()
            ? utils()->response()
                ->status( 'error' )
                ->submit( $this->getMessage(), [] )
            : utils()->response()
                ->template('errors.490')
                ->view( $this->getMessage() );
    }
}
