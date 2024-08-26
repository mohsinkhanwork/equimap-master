<?php

namespace App\Policies;

use App\Models\Horse;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HorsePolicy{
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
        return $user->can( 'view horse' );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can( 'create horse' );
    }

    /**
     * @param User $user
     * @param Horse $horse
     * @return bool
     */
    public function edit( User $user, Horse $horse ){
        return $user->can( 'edit horse' ) && $horse->whereHas( 'provider' );
    }

    /**
     * @param User $user
     * @param Horse $horse
     * @return bool
     */
    public function delete( User $user, Horse $horse ){
        return $user->can( 'delete horse' ) && $horse->whereHas( 'provider' );
    }
}
