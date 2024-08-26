<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Provider;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderPolicy{
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
     * @return mixed
     */
    public function index( User $user ){
        return $user->can( 'view provider' );
    }


    /**
     * @param User $user
     * @return mixed
     */
    public function create( User $user ){
        return $user->can( 'create provider' );
    }


    /**
     * @param User $user
     * @param Provider $provider
     * @return mixed
     */
    public function edit( User $user, Provider $provider ){
        return $user->can( 'edit provider' ) && $provider->user_id == $user->id;
    }


    /**
     * @param User $user
     * @param Provider $provider
     * @return mixed
     */
    public function delete_images( User $user, Provider $provider ){
        return $provider->user_id == $user->id;
    }
}
