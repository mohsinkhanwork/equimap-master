<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FacilityPolicy {
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
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @return mixed
     */
    public function index( User $user ){
        return $user->can( 'view facility' );
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can('create facility');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @return Response|bool
     */
    public function edit( User $user ){
        return $user->can('edit facility');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return Response|bool
     */
    public function delete( User $user ){
        return $user->can('delete facility');
    }
}
