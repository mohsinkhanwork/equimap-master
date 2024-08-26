<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BookingPolicy {
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
        return $user->can( 'view booking' );
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Booking $booking
     * @return mixed
     */
    public function show( User $user, Booking $booking ){
        return $user->can( 'view booking' ) && $booking->user_id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create( User $user ){
        return $user->can('create booking');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Booking $booking
     * @return Response|bool
     */
    public function edit( User $user ){
        return $user->can('edit booking');
    }


    /**
     * Determine whether the user can reschedule the booking.
     *
     * @param User $user
     * @param Booking $booking
     * @return Response|bool
     */
    public function reschedule( User $user, Booking $booking ){
        return $booking->user_id == $user->id;
    }


    /**
     * Determine whether the user can cancel the booking.
     *
     * @param User $user
     * @param Booking $booking
     * @return Response|bool
     */
    public function cancel( User $user, Booking $booking ){
        return $booking->user_id == $user->id;
    }
}
