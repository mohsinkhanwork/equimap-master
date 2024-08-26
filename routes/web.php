<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::name('web.')->prefix('')->group( function( $route ){
    $route->get( 'language', [ App\Http\Controllers\Web\LanguageController::class, 'change' ] )->name('language.change');
    $route->match( [ 'GET', 'POST' ], 'booking/check-in', [ App\Http\Controllers\Web\BookingController::class, 'checkin' ] )
                        ->name('web.booking.checkin');

    $route->name('users.')->prefix('users')->group(function( $route ){
        $route->get( 'delete', [ App\Http\Controllers\Web\UserController::class, 'delete' ] )->name('delete');
        $route->delete( 'destroy', [ App\Http\Controllers\Web\UserController::class, 'destroy' ] )->name('destroy');
    });

    $route->name('pages.')->prefix('page')->group(function( $route ){
        $route->get( '{page_slug}', [ App\Http\Controllers\Web\PageController::class, 'show' ] )->name('show');
    });

    $route->get('/providers/{provider_slug}/{provider_id}', [ App\Http\Controllers\Web\ProviderController::class, 'show' ] )->name('providers.show');
    $route->get('/trips/{trip_slug}/{trip_id}', [ App\Http\Controllers\Web\TripController::class, 'show' ] )->name('trips.show');
    $route->get('/courses/{course_slug}/{course_id}', [ App\Http\Controllers\Web\CourseController::class, 'show' ] )->name('course.show');
});
