<?php

namespace App\Policies;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainerPolicy{
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
        return $user->can( 'view trainer' );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can( 'create trainer' );
    }

    /**
     * @param User $user
     * @param Trainer $trainer
     * @return bool
     */
    public function edit( User $user, Trainer $trainer ){
        return $user->can( 'edit trainer' ) && $trainer->whereHas( 'provider' );
    }

    /**
     * @param User $user
     * @param Trainer $trainer
     * @return bool
     */
    public function delete( User $user, Trainer $trainer ){
        return $user->can( 'delete trainer' ) && $trainer->whereHas( 'provider' );
    }
}
