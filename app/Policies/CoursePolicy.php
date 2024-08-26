<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy{
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
        return $user->can( 'view course' );
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can( 'create course' );
    }

    /**
     * @param User $user
     * @param Course $courses
     * @return bool
     */
    public function edit(User $user, Course $courses ){
        return $user->can( 'edit course' ) && $courses->whereHas( 'provider' );
    }

    /**
     * @param User $user
     * @param Course $courses
     * @return bool
     */
    public function delete(User $user, Course $courses ){
        return $user->can( 'delete course' ) && $courses->whereHas( 'provider' );
    }
}
