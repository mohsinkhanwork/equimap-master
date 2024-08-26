<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy{
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
        return $user->can( 'view package' );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can( 'create package' );
    }

    /**
     * @param User $user
     * @param Trainer $package
     * @return bool
     */
    public function edit( User $user, Package $package ){
        return $user->can( 'edit package' ) && $package->whereHas( 'provider' );
    }

    /**
     * @param User $user
     * @param Package $package
     * @return bool
     */
    public function delete( User $user, Package $package ){
        return $user->can( 'delete package' ) && $package->whereHas( 'provider' );
    }
}
