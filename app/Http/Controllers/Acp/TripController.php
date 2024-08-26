<?php


namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\TripDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trip\TripItineraryStoreRequest;
use App\Http\Requests\Trip\TripUpdateRequest;
use App\Http\Requests\Trip\TripStoreRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\Provider;
use App\Models\Trip;

class TripController extends Controller {
    public function index( TripDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Trip::class );
        addJavascriptFile('assets/js/pages/trip.listing.js');

        return $dataTable->render('pages.trips.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( Trip::class );
        addJavascriptFile('assets/js/pages/trip.create.js');

        return view('pages.trips.create', [
            'providers' => Provider::all(),
            'categories'=> Category::all(),
            'countries' => Country::all(),
            'types'     => Trip::getTripTypes()
        ]);
    }

    public function store( TripStoreRequest $request, Trip $trips ){
        ValidatePermission::authorizeWeb( Trip::class, 'create' );

        $store      = $trips->create( $request->validated() );
        if( $store->id > 0 && $request->hasFile('gallery') ){
            foreach( $request->file('gallery') as $file ){
                $store->uploadImage( $file, 'gallery' );
            }
        }

        return $store->id > 0
                    ? $this->success( 'api/trip.create.success', $store )
                    : $this->error( 'api/trip.create.failed' );
    }

    public function edit( Trip $trips, $trip_id ){
        $trip       = $trips->with('images')->find( $trip_id );
        ValidatePermission::authorizeWeb( $trip );
        addJavascriptFile('assets/js/pages/trip.create.js');

        return view('pages.trips.edit', [
            'trip'      => $trip,
            'providers' => Provider::all(),
            'categories'=> Category::all(),
            'countries' => Country::all(),
            'types'     => Trip::getTripTypes()
        ]);
    }

    public function update( TripUpdateRequest $request, Trip $trips, $trip_id ){
        $trip       = $trips->find( $trip_id );
        ValidatePermission::authorizeWeb( $trip, 'edit' );

        $updated    = $trip->update( $request->validated() );
        if( $updated && $request->hasFile('gallery') ){
            foreach( $request->file('gallery') as $file ){
                $trip->uploadImage( $file, 'gallery' );
            }
        }

        return $updated
                ? $this->success( 'api/trip.update.success' )
                : $this->error( 'api/trip.update.failed' );
    }

    public function itinerary( Trip $trips, $trip_id ){
        $trip       = $trips->with('itinerary')->find( $trip_id );
        ValidatePermission::authorizeWeb( $trip, 'edit' );
        addJavascriptFile('assets/js/pages/trip.itinerary.create.js');

        return view('pages.trips.create_itinerary', [
            'trip'      => $trip,
            'itinerary' => $trip->itinerary()->get()->groupByDate()
        ]);
    }

    public function store_itinerary( TripItineraryStoreRequest $request, Trip $trips, $trip_id ){
        $trip       = $trips->find( $trip_id );
        ValidatePermission::authorizeWeb( $trip, 'edit' );

        $stored     = 0;
        foreach( $request->validated('trip') as $date => $itinerary ){
            // if id is available than update and continue
            if( isset( $itinerary['id'] ) && $itinerary['id'] > 0 ){
                if( $trip->itinerary()->find( $itinerary['id'] )->update( $itinerary ) ){
                    $stored++;
                    continue;
                };
            }

            $store  = $trip->itinerary()->create([
                'description'   => $itinerary['description'],
                'date'          => $date
            ]);

            if( $store->id > 0 ){
                $stored++;
            }
        }

        return $stored > 0
                    ? $this->success( 'api/trip.itinerary.create.success' )
                    : $this->error( 'api/trip.itinerary.create.failed' );
    }

    public function destroy( Trip $trips, $trip_id ){
        $trip       = $trips->find( $trip_id );
        ValidatePermission::authorizeWeb( $trip, 'delete' );

       return $trip->delete()
                    ? $this->success( 'api/trip.delete.success' )
                    : $this->error( 'api/trip.delete.failed' );
    }

    public function destroy_image( Trip $trips, $trip_id, $image_id ){
        $trip   = $trips->find( $trip_id );
        ValidatePermission::authorizeWeb( $trip, 'delete trip images' );

        $images     = $trip->images()->find( $image_id );
        return $images && $images->delete()
                    ? $this->success( 'api/trips.image.destroy.success' )
                    : $this->error( 'api/trips.image.destroy.failed' );
    }

    public function set_cover_image( Trip $trips, $trip_id, $image_id ){
        $trip   = $trips->find( $trip_id );
        ValidatePermission::authorizeWeb( $trip, 'update trip images' );

        // set all images as gallery first
        $images         = $trip->images();
        $images->update(['type'=>'gallery']);

        // set selected image as primary
        $selectedImage  = $images->find( $image_id );
        $updated        = $selectedImage->update(['type'=>'cover']);

        return $updated
            ? $this->success( 'api/trips.image.cover.success' )
            : $this->error( 'api/trips.image.cover.failed' );
    }
}
