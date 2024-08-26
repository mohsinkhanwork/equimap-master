<?php
return [
    'permanent_super_admin' => env('PERMANENT_SUPER_ADMIN', 1 ),
    'payment_code_length'   => env( 'PAYMENT_CODE_LENGTH', 8 ),
    'register'          => [
        'vendor_role'   => env( 'DEFAULT_VENDOR_ROLE', 'vendor' ),
        'customer_role' => env( 'DEFAULT_CUSTOMER_ROLE', 'customer' ),
    ],
    'service'           => [
        'default_notes' => env( 'SERVICE_DEFAULT_NOTES', 'Service is pending approval.' ),
    ],
    'course'           => [
        'default_notes' => env('COURSE_DEFAULT_NOTES', 'Course is pending approval.' ),
    ],
    'package'           => [
        'default_notes' => env('PACKAGE_DEFAULT_NOTES', 'Package is pending approval.' ),
    ],
    'results_per_page'  => env( 'RESULTS_PER_PAGE', 20 ),
    'default_package_type' => env( 'DEFAULT_PACKAGE_TYPE', 'service' ),
];
