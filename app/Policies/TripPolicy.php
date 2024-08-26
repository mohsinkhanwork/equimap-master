<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TripPolicy{
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
        return $user->can( 'view trip' );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can( 'create trip' );
    }

    /**
     * @param User $user
     * @param Trip $trip
     * @return bool
     */
    public function edit( User $user, Trip $trip ){
        return $user->can( 'edit trip' ) && $trip->whereHas( 'provider' );
    }

    /**
     * @param User $user
     * @param Trip $trip
     * @return bool
     */
    public function delete( User $user, Trip $trip ){
        return $user->can( 'delete trip' ) && $trip->whereHas( 'provider' );
    }
}
