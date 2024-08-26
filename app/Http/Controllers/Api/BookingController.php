<?php


namespace App\Http\Controllers\Api;

use App\Actions\ValidatePermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\BookingPackageAvailabilityRequest;
use App\Http\Requests\Booking\BookingServiceAvailabilityRequest;
use App\Http\Requests\Booking\BookingTripAvailabilityRequest;
use App\Http\Requests\Booking\BookingRescheduleRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Services\BookingPackageAvailabilityService;
use App\Services\BookingServiceAvailabilityService;
use App\Services\BookingTripAvailabilityService;

class BookingController extends Controller{
    public function index( Booking $booking ){
        ValidatePermission::authorizeApi( Booking::class );

        $bookings   = $booking
                            ->self()
                            ->with([ 'bookable', 'children' ])
                            ->whereNull( 'parent_id' )
                            ->orderByDesc('id')
                            ->paginate();

        return $bookings->isNotEmpty()
                    ? $this->success( 'api/booking.read.success', $bookings )
                    : $this->notfound( 'api/booking.read.not_found' );
    }

    public function show( Booking $bookings, $booking_id ){
        $booking    = $bookings::with([
                            'bookable',
                            'children',
                            'transactions',
                            'horse', 'trainer'
                        ])->find( $booking_id );
        ValidatePermission::authorizeApi( $booking, 'show' );

        return !empty( $booking )
                    ? $this->success( 'api/booking.read.success', $booking )
                    : $this->notfound( 'api/booking.read.not_found' );
    }

    public function reschedule( BookingRescheduleRequest $request, BookingServiceAvailabilityService $availabilityService, Booking $bookings, $booking_id ){
        $booking    = $bookings::find( $booking_id );
        ValidatePermission::authorizeApi( $booking, 'reschedule' );

        // If reschedule time has not lapsed
        if( $booking->rescheduleable == false ){
            return $this->error( 'api/booking.reschedule.lapsed', $booking, [ 'time' => $booking->rescheduleable_until ] );
        }

        // If new date can be accommodated
        $availability   = $availabilityService
                            ->setup(
                                $request->date,
                                $request->bookable_id,
                                $request->schedule_id,
                                $request->only( [ 'horse_id', 'trainer_id', 'booking_id', 'notes', 'coupon' ] )
                            );

        // if payment is required then this should be called after payment
        $serviceCharges = $availability->getServiceCharges();
        if( !$availability->canSchedule() || $serviceCharges['booking']['payment_required'] == true ){
            return $this->error( 'api/booking.reschedule.failed' );
        }

        // All is well change the schedule
        return $booking->update([
                'horse_id'  => $availability->getHorseId(),
                'trainer_id'=> $availability->getTrainerId(),
                'start_time'=> $availability->getStartDateTime(),
                'end_time'  => $availability->getEndDateTime(),
                'status'    => Service::getScheduledStatus(),
                'notes'     => $availability->getNotes() != '' ? $availability->getNotes() : $booking->notes
            ])
                ? $this->success( 'api/booking.reschedule.success', $bookings )
                : $this->error( 'api/booking.reschedule.failed' );
    }

    public function packageAvailability( BookingPackageAvailabilityRequest $request, BookingPackageAvailabilityService $bookingPackageAvailabilityService ){
        // setup service with basic params
        $availability   = $bookingPackageAvailabilityService->setup( $request );

        // we are all good lets calculate service charges now
        $serviceCharges = $availability->getServiceCharges();
        return $this->success( 'api/booking.availability.available', $serviceCharges );
    }

    public function tripAvailability( BookingTripAvailabilityRequest $request, BookingTripAvailabilityService $bookingTripAvailabilityService ){
        // setup service with basic params
        $availability   = $bookingTripAvailabilityService->setup( $request );

        // if capacity is reached for the service
        if( $availability->isServiceSlotReserved() ){
            return $this->error( 'api/booking.availability.service_capacity_reached', null, [ 'date' => $availability->getStartDate() ] );
        }

        // we are all good lets calculate service charges now
        $serviceCharges = $availability->getServiceCharges();
        return $this->success( 'api/booking.availability.available', $serviceCharges );
    }

    public function serviceAvailability( BookingServiceAvailabilityRequest $request, BookingServiceAvailabilityService $availabilityService ){
        // setup service with basic params
        $availability   = $availabilityService
                            ->setup(
                                $request->date,
                                $request->bookable_id,
                                $request->schedule_id,
                                $request->only( [ 'horse_id', 'trainer_id', 'booking_id', 'notes', 'coupon' ] )
                            );

        // is schedule available ?
        if( $availability->isScheduleNotActive() ){
            return $this->error( 'api/booking.availability.schedule_invalid' );
        }

        // is this schedule for service
        if( $availability->isScheduleNotService() ){
            return $this->error( 'api/booking.availability.schedule_not_service' );
        }

        // do we have schedule available at given date and slot
        if( $availability->isScheduleUnavailable() ){
            return $this->error( 'api/booking.availability.schedule_unavailable', null, [ 'date' => $availability->getDate() ] );
        }

        // do we have any block on any of the entities being requested
        if( $isCalendarBlocked = $availability->isCalendarBlocked() ){
            return $this->error( 'api/booking.availability.calendar_blocked',
                [ 'reason'  => $availability->getCalendars()->first()->notes ],
                [ 'type'    => $availability->getCalendars()->first()->getCalendarType() ] );
        }

        // if capacity is reached for the service
        if( $availability->isServiceSlotReserved() ){
            return $this->error( 'api/booking.availability.service_capacity_reached', null, [ 'date' => $availability->getDate() ] );
        }

        // if horse is not available
        if( $availability->isHorseUnavailable() ){
            return $this->error( 'api/booking.availability.entity_reserved', null, [ 'date' => $availability->getDate(), 'type' => 'horse' ] );
        }

        // if trainer is not available
        if( $availability->isTrainerUnavailable() ){
            return $this->error( 'api/booking.availability.entity_reserved', null, [ 'date' => $availability->getDate(), 'type' => 'trainer' ] );
        }

        // we are all good lets calculate service charges now
        $serviceCharges = $availability->getServiceCharges();
        return $this->success( 'api/booking.availability.available', $serviceCharges );
    }

    public function cancel( Booking $bookings, $booking_id ){
        $booking    = $bookings::find( $booking_id );
        ValidatePermission::authorizeApi( $booking, 'cancel' );

        // confirm if booking is cancellable
        if( !$booking->cancelable ){
            return $this->error('api/booking.cancel.time_lapsed');
        }

        $updated    = $booking->update(['status'=>'cancelled']);
        return $updated
                    ? $this->success( 'api/booking.cancel.success', $booking )
                    : $this->notfound( 'api/booking.cancel.failed' );
    }
}
