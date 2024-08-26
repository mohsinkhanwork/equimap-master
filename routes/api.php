<?php

use Illuminate\Support\Facades\Route;

/*
 * OPEN ROUTES (NO AUTHENTICATION REQUIRED)
 */
Route::group( [], function( $route ){
    // AUTHENTICATION
    $route->group( [ 'prefix' => 'users' ], function( $route ){
        $route->post( 'register', [ App\Http\Controllers\Api\UserController::class, 'store' ] );
        $route->post( 'login', [ App\Http\Controllers\Api\UserController::class, 'login' ] );
        $route->patch( 'verify', [ App\Http\Controllers\Api\UserController::class, 'verify' ] );
        $route->get( 'reset', [ App\Http\Controllers\Api\UserController::class, 'resetPassword' ] );
        $route->patch( 'reset', [ App\Http\Controllers\Api\UserController::class, 'updatePassword' ] );
    });

    // PROVIDERS
    $route->group( [ 'prefix' => 'providers' ], function( $route ){
        $route->get( '', [ App\Http\Controllers\Api\ProviderController::class, 'index' ] );
        $route->get( '/search', [ App\Http\Controllers\Api\ProviderController::class, 'search' ] );
        $route->get( '/{provider_id}', [ App\Http\Controllers\Api\ProviderController::class, 'show' ] );
    });

    // SERVICES
    $route->group( [ 'prefix' => 'services' ], function( $route ){
        $route->get( '', [ App\Http\Controllers\Api\ServiceController::class, 'index' ] );
        $route->get( '{service_id}', [ App\Http\Controllers\Api\ServiceController::class, 'show' ] );
        $route->get( '{service_id}/schedules', [ App\Http\Controllers\Api\ServiceController::class, 'schedules' ] );
        $route->get( '{service_id}/availability', [ App\Http\Controllers\Api\ServiceController::class, 'availability' ] );
        $route->get( '{service_id}/reviews', [ App\Http\Controllers\Api\ServiceController::class, 'reviews' ] );
    });

    // PACKAGES
    $route->group( [ 'prefix' => 'packages' ], function( $route ){
        $route->get( '', [ App\Http\Controllers\Api\PackageController::class, 'index' ] );
        $route->get( '{package_id}', [ App\Http\Controllers\Api\PackageController::class, 'show' ] );
    });

    // TRIPS
    $route->group( [ 'prefix' => 'trips' ], function( $route ){
        $route->get( '', [ App\Http\Controllers\Api\TripController::class, 'index' ] );
        $route->get( '{trip_id}', [ App\Http\Controllers\Api\TripController::class, 'show' ] );
    });

    // CATEGORY ROUTES
    $route->group( [ 'prefix' => 'categories'], function( $route ){
        $route->get( '', [ App\Http\Controllers\Api\CategoryController::class, 'index' ] );
        $route->get( '{category_id}/services', [ App\Http\Controllers\Api\CategoryController::class, 'services' ] );
        $route->get( '{category_id}/providers', [ App\Http\Controllers\Api\CategoryController::class, 'providers' ] );
    });

    // ADDITIONAL ROUTES
    $route->get( 'bookings/availability', [ App\Http\Controllers\Api\BookingController::class, 'serviceAvailability' ] );
    $route->get( 'bookings/availability/service', [ App\Http\Controllers\Api\BookingController::class, 'serviceAvailability' ] );
    $route->get( 'bookings/availability/trip', [ App\Http\Controllers\Api\BookingController::class, 'tripAvailability' ] );
    $route->get( 'bookings/availability/package', [ App\Http\Controllers\Api\BookingController::class, 'packageAvailability' ] );
    $route->get( '/horses', [ App\Http\Controllers\Api\HorseController::class, 'index' ] );
    $route->get( '/trainers', [ App\Http\Controllers\Api\TrainerController::class, 'index' ] );
    $route->get( '/facilities', [ App\Http\Controllers\Api\FacilityController::class, 'index' ] );
    $route->get( '/banners', [ App\Http\Controllers\Api\BannerController::class, 'index' ] );
    // $route->get( '/payment-methods', [ App\Http\Controllers\Api\PaymentMethodController::class, 'index' ] );
});


/*
 * GATED ROUTES (USER NEEDS TO BE AUTHENTICATED)
 */
Route::group( [ 'middleware' => 'auth:sanctum' ], function( $route ){
    // USER
    $route->group( [ 'prefix' => 'users' ], function( $route ){
        $route->patch( '', [ App\Http\Controllers\Api\UserController::class, 'update' ] );
        $route->delete( '', [ App\Http\Controllers\Api\UserController::class, 'destroy' ] );
        $route->get( '/profile', [ App\Http\Controllers\Api\UserProfileController::class, 'show' ] );
        $route->patch( '/profile', [ App\Http\Controllers\Api\UserProfileController::class, 'update' ] );
        $route->patch( '/profile', [ App\Http\Controllers\Api\UserProfileController::class, 'update' ] );
    });

    // REVIEWS
    $route->group( [ 'prefix' => 'reviews' ], function( $route ) {
        $route->post( '', [ App\Http\Controllers\Api\ReviewController::class, 'store' ] );
    });

    // BOOKING ROUTES
    $route->group( [ 'prefix' => 'bookings'], function( $route ){
        $route->get( '', [ App\Http\Controllers\Api\BookingController::class, 'index' ] );
        $route->get( '/{booking_id}', [ App\Http\Controllers\Api\BookingController::class, 'show' ] );
        $route->get( '/{booking_id}/reschedule', [ App\Http\Controllers\Api\BookingController::class, 'reschedule' ] );
        $route->get( '/{booking_id}/cancel', [ App\Http\Controllers\Api\BookingController::class, 'cancel' ] );
    });

    // TRANSACTIONS
    $route->group( [ 'prefix' => 'payment' ], function( $route ) {
        $route->get( '', [ App\Http\Controllers\Api\TransactionController::class, 'init' ] )->name( 'pay' );
        $route->get( 'success', [ App\Http\Controllers\Api\TransactionController::class, 'paid' ] )->name( 'pay.success' );
        $route->get( 'cancelled', [ App\Http\Controllers\Api\TransactionController::class, 'cancelled' ] )->name( 'pay.cancelled' );
    });

    // FAVORITES
    $route->group( [ 'prefix' => 'favorites' ], function( $route ){
        $route->get( '', [ App\Http\Controllers\Api\FavoriteController::class, 'index' ] );
        $route->post( '', [ App\Http\Controllers\Api\FavoriteController::class, 'store' ] );
        $route->delete( '/{favorite_id}', [ App\Http\Controllers\Api\FavoriteController::class, 'destroy' ] );
    });
});

/*
 * GATED ROUTES (USER NEEDS TO BE AUTHENTICATED & AUTHORIZED )
 * USER ROLES: Admin or Vendor
 */
Route::group( [ 'middleware' => [ 'auth:sanctum', 'ability:admin,vendor' ] ], function( $route ){
    // PROVIDERS
    $route->group( ['prefix' => 'providers'], function( $route ){
        $route->post( '', [ App\Http\Controllers\Api\ProviderController::class, 'store' ] );
        $route->post( '/{provider_id}/images/{images_type}', [ App\Http\Controllers\Api\ProviderController::class, 'store_images' ] );
        $route->put( '/{provider_id}', [ App\Http\Controllers\Api\ProviderController::class, 'update' ] );
    });

    // SERVICES
    $route->group( ['prefix' => 'services' ], function( $route ) {
        $route->post('', [App\Http\Controllers\Api\ServiceController::class, 'store']);
    });

    // SCHEDULES
    $route->group( ['prefix' => 'schedules' ], function( $route ) {
        $route->post('/{scheduleable_type}/{scheduleable_id}', [App\Http\Controllers\Api\ScheduleController::class, 'store']);
    });
});

/*
 * GATED ROUTES (USER NEEDS TO BE AUTHENTICATED & AUTHORIZED )
 * USER ROLES: Admin
 */
Route::group( [ 'middleware' => [ 'auth:sanctum', 'ability:admin' ] ], function( $route ){
    $route->post( '/facilities', [ App\Http\Controllers\Api\FacilityController::class, 'store' ] );
    $route->post( '/categories', [ App\Http\Controllers\Api\CategoryController::class, 'store' ] );
    $route->post( '/categories/{category_id}/icon', [ App\Http\Controllers\Api\CategoryController::class, 'store_icon' ] );
});
