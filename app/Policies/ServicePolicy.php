<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Service;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function before( User $user ){
        if( $user->hasRole( 'super admin' ) ){
            return true;
        }
    }


    /**
     * @param User $user
     * @return bool
     */
    public function index( User $user ){
        return $user->can( 'view service' );
    }


    /**
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can( 'create service' );
    }


    /**
     * @param User $user
     * @param Service $service
     * @return bool
     */
    public function edit( User $user, Service $service ){
        return $user->can( 'edit service' )
            && $service->whereHas( 'provider' );
    }
}
