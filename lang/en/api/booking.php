<?php
return [
    'reschedule'    => [
        'failed'    => 'Failed to reschedule booking',
        'lapsed'    => 'Rescheduling was only allowed until :time on this booking.',
        'success'   => 'Booking is scheduled successfully.',
    ],
    'availability'  => [
        'available'                 => 'Calendar availability is listed successfully.',
        'schedule_invalid'          => 'Invalid schedule.',
        'calendar_blocked'          => 'All slots are blocked for requested :type.',
        'schedule_unavailable'      => 'No slots are available for requested date (:date).',
        'service_capacity_reached'  => 'All available slots for requested service are reserved for this date (:date).',
        'entity_reserved'           => 'All available slots for requested :type are reserved for this date (:date).',
        'schedule_not_service'      => 'Requested schedule does not belong to a service.',
        'failed'                    => 'Failed to find available schedule.'
    ],
    'not_found'     => 'Booking reference is invalid or expired.',
    'cancel'            => [
        'success'       => 'Booking is cancelled successfully, we will process your refund (if required) within 7 business days.',
        'failed'        => 'Failed to cancel booking.',
        'time_lapsed'   => 'Time to cancel has lapsed or booking is not scheduled yet.'
    ]
];
