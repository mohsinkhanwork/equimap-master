<?php

namespace App\Actions;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class StopExecutionAction{
    public static function handle( $message, $code ){
        throw new BadRequestException( $message, $code );
    }
}
