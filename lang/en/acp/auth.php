<?php
return [
    'login'     => [
        'fields'    => [
            'login'     => 'Phone Number',
            'password'  => 'Password',
            'submit'    => 'Login'
        ],
        'title'     => 'Administration Panel',
        'subtitle'  => 'Please login below to continue.',
        'reset'     => 'Forgot password ?',
        'invalid_credentials'=> 'Your login credentials are invalid.',
        'disabled'          => 'Your account is disabled, please contact support@equimap.app',
        'unverified'        => 'Your account is not verified, please login via mobile app to verify account.',
        'invalid_role'      => 'You are not authorized to access this area.',
        'not_member'    => 'Not yet mapping ?',
        'register'      => 'Register now',
        'success'       => 'Logged in successfully.'
    ],
    'register'  => [
        'fields'    => [
            'name'      => 'Full Name',
            'login'     => 'Phone Number',
            'password'  => 'Password',
            'password_confirmation'  => 'Confirm Password',
            'submit'    => 'Register'
        ],
        'title'         => 'Register',
        'subtitle'      => 'Please fill in the form below to register',
        'password_hint' => 'Use 8 or more characters with a mix of upper and lower case letters, numbers & symbols.',
        'agree_terms'   => 'I agree to the <a href=":link_terms" class="link-primary">terms</a> and <a href=":link_privacy">privacy</a> conditions of :business_name.',
        'already_member'=> 'Already a member ?',
        'login'         => 'Login now'
    ],
    'verify'    => [
        'title'     => 'Phone Number Verification',
        'subtitle'  => 'Please enter OTP code sent to :phone_number',
        'fields'    => [
            'submit'    => 'Verify Phone'
        ]
    ]
];
