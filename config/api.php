<?php
return [
    'default_user_role' => env( 'DEFAULT_USER_ROLE', 'customer' ),
    'token_name'        => env( 'API_TOKEN_NAME', 'api-token' ),
    'contact_email'     => env( 'CONTACT_EMAIL', 'support@equimap.app' ),
    'reschedule_status' => explode(',', env( 'RESCHEDULE_STATUS', 'scheduled,pending' ) ),
    'reschedule_time'   => env( 'RESCHEDULE_HOURS', 24 ),
    'cancel_status'     => explode(',', env( 'CANCEL_STATUS', 'scheduled' ) ),
    'cancel_time'       => env( 'CANCEL_HOURS', 24 ),
];
