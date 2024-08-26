<?php

namespace App\Policies;

use App\Models\User;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy{
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
        return $user->can( 'view user' );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can( 'create user' );
    }

    /**
     * @param User $user
     * @param UserModel $userModel
     * @return bool
     */
    public function edit( User $user, UserModel $userModel ){
        return $user->can( 'edit user' ) || ( $user->id == $userModel->id && !$userModel->hasRole('super admin') );
    }

    /**
     * @param User $user
     * @param UserModel $userModel
     * @return bool
     */
    public function verify( User $user, UserModel $userModel ){
        return $user->can( 'verify user' ) || $user->id == $userModel->id;
    }

    /**
     * @param User $user
     * @param UserModel $userModel
     * @return bool
     */
    public function delete( User $user, UserModel $userModel ){
        return $user->can( 'delete user' ) || $user->id == $userModel->id;
    }
}
