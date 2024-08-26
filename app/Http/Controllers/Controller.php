<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __call( $method, $parameters ){
        if( !in_array( $method, [ 'created', 'success', 'error', 'unauthenticated',  'notfound', 'redirect'] ) ){
            return $this->response( 'notfound', 'api/general.route_not_found' );
        }

        return $this->response( $method, ...$parameters );
    }

    public function view( $type, $template, $message, $body, $messageReplacers=[] ){
        if( in_array( $type, [ 'success', 'error' ] ) ) {
            return utils()
                ->response()
                ->status($type)
                ->items($body)
                ->template($template)
                ->view($message, $messageReplacers);
        }
    }

    public function viewSuccess( $template, $message, $body=[], $messageReplacers=[] ){
        return $this->view( 'success', $template, $message, $body, $messageReplacers );
    }

    public function viewError( $template, $message, $body=[], $messageReplacers=[] ){
        return $this->view( 'error', $template, $message, $body, $messageReplacers );
    }

    public function viewForbidden( $message='' ){
        return $this->view( 'forbidden', '', $message, '' );
    }

    public function response( $type, $message, $body=[], $messageReplacers=[] ){
        if( in_array( $type, [ 'created', 'success', 'error', 'unauthenticated', 'notfound', 'redirect'] ) ){
            return utils()
                ->response()
                ->status( $type )
                ->items( $body )
                ->submit( $message, $messageReplacers );
        }
    }
}
