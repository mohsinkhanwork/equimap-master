<?php


namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\ProvidersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\ProviderUpdateRequest;
use App\Models\Facility;
use App\Models\Provider;
use App\Http\Requests\Provider\ProviderStoreRequest;

class ProviderController extends Controller {
    public function index( ProvidersDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Provider::class );
        return $dataTable->render('pages.providers.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( Provider::class );

        $mapKey     = config('geocoder.key');
        addJavascriptFile('assets/js/pages/provider.create.js');
        addJavascriptFile('https://maps.googleapis.com/maps/api/js?key='.$mapKey.'&callback=initAutocomplete&libraries=places&v=weekly');

        return view('pages.providers.create', [ 'facilities' => Facility::all() ] );
    }

    public function store( ProviderStoreRequest $request, Provider $providers ){
        ValidatePermission::authorizeWeb( Provider::class, 'create' );

        $store  = $providers->create( $request->all() );

        // add facilities data
        if( $request->has('facility') ){
            $store->provider_facilities()->sync( $request->facility );
        }

        // upload cover image
        if( $store->id > 0 && $request->hasFile('cover') ){
            $store->uploadCover( $request->file('cover'), 'cover' );
        }

        // upload gallery images
        if( $store->id > 0 && $request->hasFile('gallery') ){
            foreach( $request->file('gallery') as $file ){
                $store->uploadImage( $file, 'gallery' );
            }
        }

        return $store->id > 0
            ? $this->success( 'api/providers.create.success', $store )
            : $this->error( 'api/providers.create.failed' );
    }


    public function edit( Provider $providers, $provider_id ){
        // fetch provider and make sure user has relevant permissions
        $provider   = $providers->with( [ 'courses', 'trips','services','facilities', 'images'] )->find( $provider_id );
        ValidatePermission::authorizeWeb( $provider );

        // make sure we are able to load google maps
        $mapKey     = config('geocoder.key');
        addJavascriptFile('assets/js/pages/provider.create.js');
        addJavascriptFile('https://maps.googleapis.com/maps/api/js?key='.$mapKey.'&callback=initAutocomplete&libraries=places&v=weekly');

        // get flat id of provider facilities to display in dropdown multi-select
        $provider->facilities   = $provider->facilities->pluck('id')->toArray();

        return view('pages.providers.edit', [
            'provider'      => $provider,
            'services'      => $provider->services,
            'trips'         => $provider->trips,
            'horses'        => $provider->horses,
            'trainers'      => $provider->trainers,
            'courses'       => $provider->courses,
            'facilities'    => Facility::all()
        ]);
    }

    public function update( ProviderUpdateRequest $request, Provider $providers, $provider_id ){
        // fetch provider and make sure user has relevant permissions
        $provider   = $providers->find( $provider_id );
        ValidatePermission::authorizeWeb( $provider, 'edit' );

        // update provider data first and than facilities
        $updated    = $provider->update( $request->validated() );

        // sync facilities data
        if( $updated && $request->has('facilities') ){
            $provider->provider_facilities()->sync( $request->facilities );
        }

        // upload cover image
        if( $updated && $request->hasFile('cover') ){
            $provider->uploadCover( $request->file('cover'), 'cover' );
        }

        // upload gallery images
        if( $updated && $request->hasFile('gallery') ){
            foreach( $request->file('gallery') as $file ){
                $provider->uploadImage( $file, 'gallery' );
            }
        }

        return $updated
                    ? $this->success( 'api/providers.update.success' )
                    : $this->error( 'api/providers.update.failed' );
    }

    public function destroy_image( Provider $providers, $provider_id, $image_id ){
        $provider   = $providers->find( $provider_id );
        ValidatePermission::authorizeWeb( $provider, 'delete provider images' );

        $images     = $provider->images()->find( $image_id );
        return $images && $images->delete()
                            ? $this->success( 'api/providers.image.destroy.success' )
                            : $this->error( 'api/providers.image.destroy.failed' );
    }
}
