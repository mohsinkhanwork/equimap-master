<?php
return [
    'days'  => [
        'sunday'    => 'Sunday',
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
        'saturday'  => 'Saturday',
    ],
    'create'    => [
        'invalid_type'      => 'Invalid schedule type is provided.',
        'success'           => 'Schedule created successfully.',
        'failed'            => 'Failed to create schedule.',
        'already_exists'    => 'Provided schedule for :type either already exists or overlaps another schedule.',
        'invalid_unauthorized'  => 'Provided :type is either invalid or you are not authorized to make schedules.'
    ],
    'list'      => [
        'success'   => 'Schedules list is returned successfully.',
        'no_results'=> 'No schedules are available to display.'
    ]
];
