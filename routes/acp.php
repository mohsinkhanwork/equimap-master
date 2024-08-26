<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Acp Routes
|--------------------------------------------------------------------------
|
| Here is where you can register acp routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('language/{locale}', function ($locale) {
    app()->setLocale( $locale );
    session( [ 'locale' => $locale] );

    return redirect()->back();
})->name('lang-switcher');

Route::name('pages.')->prefix('page')->group(function( $route ){
    $route->get( '{page_slug}', [ App\Http\Controllers\Acp\PageController::class, 'show' ] )->name('show');
});

Route::group( [ 'prefix' => '' ], function( $route ){
    $route->get( '', function(){
        return redirect( route('acp.dashboard') );
    });

    $route->name('acp.auth.')->middleware( ['guest'] )->group(function( $route ){
        $route->get( 'login', [ App\Http\Controllers\Acp\AuthController::class, 'index' ] )->name('index');
        $route->post( 'login', [ App\Http\Controllers\Acp\AuthController::class, 'login' ] )->name('login');

        $route->get( 'register', [ App\Http\Controllers\Acp\AuthController::class, 'register' ] )->name('register');
        $route->post( 'register', [ App\Http\Controllers\Acp\AuthController::class, 'store' ] )->name('store');

        $route->get( 'verify', [ App\Http\Controllers\Acp\AuthController::class, 'verify' ] )->name('verify');
        $route->patch( 'verify_user', [ App\Http\Controllers\Acp\AuthController::class, 'verify_user' ] )->name('verify_user');
    });

    $route->group([ 'middleware' => 'auth:web'], function( $route ){
        $route->get( 'dashboard', [ App\Http\Controllers\Acp\DashboardController::class, 'index' ] )->name('acp.dashboard');
        $route->get( 'logout', [ App\Http\Controllers\Acp\AuthController::class, 'logout' ] )->name('acp.auth.logout');

        $route->name('acp.categories.')->prefix('categories')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\CategoryController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\CategoryController::class, 'create' ] )->name('create');
            $route->get( '{category_id}/edit', [ App\Http\Controllers\Acp\CategoryController::class, 'edit' ] )->name('edit');
            $route->patch( '{category_id}', [ App\Http\Controllers\Acp\CategoryController::class, 'update' ] )->name('update');
            $route->post( 'store', [ App\Http\Controllers\Acp\CategoryController::class, 'store' ] )->name('store');
            $route->delete( '{category_id}', [ App\Http\Controllers\Acp\CategoryController::class, 'destroy' ] )->name('destroy');
        });

        $route->name('acp.facilities.')->prefix('facilities')->group(function( $route){
            $route->get( '', [ App\Http\Controllers\Acp\FacilityController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\FacilityController::class, 'create' ] )->name('create');
            $route->get( '{facility_id}/edit', [ App\Http\Controllers\Acp\FacilityController::class, 'edit' ] )->name('edit');
            $route->patch( '{facility_id}', [ App\Http\Controllers\Acp\FacilityController::class, 'update' ] )->name('update');
            $route->post( 'store', [ App\Http\Controllers\Acp\FacilityController::class, 'store' ] )->name('store');
            $route->delete( '{facility_id}', [ App\Http\Controllers\Acp\FacilityController::class, 'destroy' ] )->name('destroy');
        });

        $route->name('acp.providers.')->prefix('providers')->group(function( $route){
            $route->get( '', [ App\Http\Controllers\Acp\ProviderController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\ProviderController::class, 'create' ] )->name('create');
            $route->post( 'store', [ App\Http\Controllers\Acp\ProviderController::class, 'store' ] )->name('store');
            $route->get( '{provider_id}/edit', [ App\Http\Controllers\Acp\ProviderController::class, 'edit' ] )->name('edit');
            $route->patch( '{provider_id}', [ App\Http\Controllers\Acp\ProviderController::class, 'update' ] )->name('update');
            $route->delete( '{provider_id}/images/{image_id}', [ App\Http\Controllers\Acp\ProviderController::class, 'destroy_image' ] )->name('destroy_image');
        });

        $route->name('acp.services.')->prefix('services')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\ServiceController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\ServiceController::class, 'create' ] )->name('create');
            $route->post( 'store', [ App\Http\Controllers\Acp\ServiceController::class, 'store' ] )->name('store');

            $route->get( '{service_id}/edit', [ App\Http\Controllers\Acp\ServiceController::class, 'edit' ] )->name('edit');
            $route->patch( '{service_id}', [ App\Http\Controllers\Acp\ServiceController::class, 'update' ] )->name('update');

            $route->get( '{service_id}/schedule', [ App\Http\Controllers\Acp\ServiceController::class, 'schedule' ] )->name('schedule');
            $route->post( '{service_id}/schedule/day', [ App\Http\Controllers\Acp\ServiceController::class, 'store_schedule_day' ] )->name('store_schedule_day');
            $route->post( '{service_id}/schedule/hour', [ App\Http\Controllers\Acp\ServiceController::class, 'store_schedule_hour' ] )->name('store_schedule_hour');

            $route->get( '{service_id}/horses', [ App\Http\Controllers\Acp\ServiceController::class, 'horses' ] )->name('horses');
            $route->post( '{service_id}/horses', [ App\Http\Controllers\Acp\ServiceController::class, 'store_horses' ] )->name('store_horses');

            $route->get( '{service_id}/trainers', [ App\Http\Controllers\Acp\ServiceController::class, 'trainers' ] )->name('trainers');
            $route->post( '{service_id}/trainers', [ App\Http\Controllers\Acp\ServiceController::class, 'store_trainers' ] )->name('store_trainers');
        });

        $route->name('acp.horses.')->prefix('horses')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\HorseController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\HorseController::class, 'create' ] )->name('create');
            $route->get( '{horse_id}/edit', [ App\Http\Controllers\Acp\HorseController::class, 'edit' ] )->name('edit');
            $route->post( 'store', [ App\Http\Controllers\Acp\HorseController::class, 'store' ] )->name('store');
            $route->patch( '{horse_id}', [ App\Http\Controllers\Acp\HorseController::class, 'update' ] )->name('update');
            $route->delete( '{horse_id}', [ App\Http\Controllers\Acp\HorseController::class, 'destroy' ] )->name('destroy');
        });

        $route->name('acp.trainers.')->prefix('trainers')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\TrainerController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\TrainerController::class, 'create' ] )->name('create');
            $route->get( '{trainer_id}/edit', [ App\Http\Controllers\Acp\TrainerController::class, 'edit' ] )->name('edit');
            $route->post( 'store', [ App\Http\Controllers\Acp\TrainerController::class, 'store' ] )->name('store');
            $route->patch( '{trainer_id}', [ App\Http\Controllers\Acp\TrainerController::class, 'update' ] )->name('update');
            $route->delete( '{trainer_id}', [ App\Http\Controllers\Acp\TrainerController::class, 'destroy' ] )->name('destroy');
        });

        $route->name('acp.trips.')->prefix('trips')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\TripController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\TripController::class, 'create' ] )->name('create');
            $route->get( '{trip_id}/edit', [ App\Http\Controllers\Acp\TripController::class, 'edit' ] )->name('edit');
            $route->post( 'store', [ App\Http\Controllers\Acp\TripController::class, 'store' ] )->name('store');
            $route->patch( '{trip_id}', [ App\Http\Controllers\Acp\TripController::class, 'update' ] )->name('update');
            $route->delete( '{trip_id}', [ App\Http\Controllers\Acp\TripController::class, 'destroy' ] )->name('destroy');

            $route->get( '{trip_id}/itinerary', [ App\Http\Controllers\Acp\TripController::class, 'itinerary' ] )->name('itinerary');
            $route->post( '{trip_id}/itinerary', [ App\Http\Controllers\Acp\TripController::class, 'store_itinerary' ] )->name('store_itinerary');

            $route->patch( '{trip_id}/images/{image_id}', [ App\Http\Controllers\Acp\TripController::class, 'set_cover_image' ] )->name('set_cover_image');
            $route->delete( '{trip_id}/images/{image_id}', [ App\Http\Controllers\Acp\TripController::class, 'destroy_image' ] )->name('destroy_image');
        });

        $route->name('acp.courses.')->prefix('courses')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\CoursesController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\CoursesController::class, 'create' ] )->name('create');
            $route->get( '{course_id}/edit', [ App\Http\Controllers\Acp\CoursesController::class, 'edit' ] )->name('edit');
            $route->post( 'store', [ App\Http\Controllers\Acp\CoursesController::class, 'store' ] )->name('store');
            $route->patch( '{course_id}', [ App\Http\Controllers\Acp\CoursesController::class, 'update' ] )->name('update');
            $route->delete( '{course_id}', [ App\Http\Controllers\Acp\CoursesController::class, 'destroy' ] )->name('destroy');

            $route->get( '{course_id}/classes', [ App\Http\Controllers\Acp\CoursesController::class, 'classes' ] )->name('classes');

            $route->get( '{course_id}/classes/create', [ App\Http\Controllers\Acp\CoursesController::class, 'create_class' ] )->name('create_class');
            $route->post( '{course_id}/classes', [ App\Http\Controllers\Acp\CoursesController::class, 'store_class' ] )->name('store_class');

            $route->get( '{course_id}/classes/{class_id}/edit', [ App\Http\Controllers\Acp\CoursesController::class, 'edit_class' ] )->name('edit_class');
            $route->patch( '{course_id}/classes/{class_id}', [ App\Http\Controllers\Acp\CoursesController::class, 'update_class' ] )->name('update_class');

            $route->delete( '{course_id}/classes/{class_id}', [ App\Http\Controllers\Acp\CoursesController::class, 'destroy_class' ] )->name('destroy_class');

            $route->get( '{course_id}/schedule', [ App\Http\Controllers\Acp\CoursesController::class, 'schedule' ] )->name('schedule');
            $route->post( '{course_id}/schedule', [ App\Http\Controllers\Acp\CoursesController::class, 'store_schedule' ] )->name('store_schedule');
        });

        $route->name('acp.packages.')->prefix('packages')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\PackageController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\PackageController::class, 'create' ] )->name('create');
            $route->get( '{package_id}/edit', [ App\Http\Controllers\Acp\PackageController::class, 'edit' ] )->name('edit');
            $route->post( 'store', [ App\Http\Controllers\Acp\PackageController::class, 'store' ] )->name('store');
            $route->patch( '{package_id}', [ App\Http\Controllers\Acp\PackageController::class, 'update' ] )->name('update');
            $route->delete( '{package_id}', [ App\Http\Controllers\Acp\PackageController::class, 'destroy' ] )->name('destroy');
        });

        $route->name('acp.pages.')->prefix('pages')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\PageController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\PageController::class, 'create' ] )->name('create');
            $route->get( '{page_id}/edit', [ App\Http\Controllers\Acp\PageController::class, 'edit' ] )->name('edit');
            $route->post( 'store', [ App\Http\Controllers\Acp\PageController::class, 'store' ] )->name('store');
            $route->patch( '{page_id}', [ App\Http\Controllers\Acp\PageController::class, 'update' ] )->name('update');
            $route->delete( '{page_id}', [ App\Http\Controllers\Acp\PageController::class, 'destroy' ] )->name('destroy');
        });

        $route->name('acp.banners.')->prefix('banners')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\BannerController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\BannerController::class, 'create' ] )->name('create');
            $route->get( '{banner_id}/edit', [ App\Http\Controllers\Acp\BannerController::class, 'edit' ] )->name('edit');
            $route->post( 'store', [ App\Http\Controllers\Acp\BannerController::class, 'store' ] )->name('store');
            $route->patch( '{banner_id}', [ App\Http\Controllers\Acp\BannerController::class, 'update' ] )->name('update');
            $route->delete( '{banner_id}', [ App\Http\Controllers\Acp\BannerController::class, 'destroy' ] )->name('destroy');
        });

        $route->name('acp.users.')->prefix('users')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\UserController::class, 'index' ] )->name('index');
            $route->get( 'create', [ App\Http\Controllers\Acp\UserController::class, 'create' ] )->name('create');
            $route->get( '{user_id}/edit', [ App\Http\Controllers\Acp\UserController::class, 'edit' ] )->name('edit');
            $route->post( 'store', [ App\Http\Controllers\Acp\UserController::class, 'store' ] )->name('store');
            $route->patch( '{user_id}', [ App\Http\Controllers\Acp\UserController::class, 'update' ] )->name('update');
            $route->delete( '{user_id}', [ App\Http\Controllers\Acp\UserController::class, 'destroy' ] )->name('destroy');
            $route->patch( 'verify/{user_id}', [ App\Http\Controllers\Acp\UserController::class, 'verify' ] )->name('verify');
        });

        $route->name('acp.bookings.')->prefix('bookings')->group(function( $route ){
            $route->get( '', [ App\Http\Controllers\Acp\BookingController::class, 'index' ] )->name('index');
        });
    });
});
