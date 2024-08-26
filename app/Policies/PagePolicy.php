<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy{
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
        return $user->can( 'view page' );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can( 'create page' );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function edit( User $user ){
        return $user->can( 'edit page' );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete( User $user ){
        return $user->can( 'delete page' );
    }
}
