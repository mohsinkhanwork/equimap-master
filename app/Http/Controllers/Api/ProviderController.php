<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\ProviderStoreRequest;
use App\Models\Image;
use App\Models\Provider;
use Axiom\Rules\LocationCoordinates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class ProviderController extends Controller {
    public function search( Request $request, Provider $provider ){
        if( !$request->has('featured')
            && !$request->has('q')
            && !$request->has('city')
        ){
            return $this->error('api/providers.search.no_params');
        }

        $providers  = $provider->newQuery();
        if( $request->has('featured') ){
            $providers
                ->where('featured', true )
                ->orderByDesc('featured_ranking');
        }

        if( $request->has('q') ){
            $providers->where('name', 'like', "%{$request->q}%");
        }

        if( $request->has('city') ){
            $providers->where('city', 'like', "%{$request->city}%");
        }

        $providers  = $providers->paginate();
        return $providers->isNotEmpty()
                    ? $this->success( 'api/providers.list.success', $providers )
                    : $this->notfound( 'api/providers.list.no_results' );
    }

    public function index( Provider $provider ){
        $providers  = $provider
                        ->whereHas('services', function( $query ){
                            return $query->active();
                        })
                        ->orWhereHas('trips', function( $query ){
                            return $query->active();
                        })
                        ->paginate( config('general.results_per_page') );

        if( $providers->isNotEmpty() ){
            return $this->success( 'api/providers.list.success', $providers );
        }

        return $this->notfound( 'api/providers.list.no_results' );
    }

    public function show( Provider $provider, $provider_id ){
        $providers  = $provider
                        ->where( 'id', $provider_id )
                        ->with( [ 'images', 'trainers','horses', 'facilities' ] )
                        ->with( 'services', function( $query ){
                            return $query->active();
                        })
                        ->with( 'trips', function( $query ){
                            return $query->active();
                        })
                        ->get();

        if( $providers->isNotEmpty() ){
            return $this->success( 'api/providers.list.success', $providers );
        }

        return $this->notfound( 'api/providers.list.no_results' );
    }

    function store( ProviderStoreRequest $request, Provider $providers ){
        return $providers->createAndReturn( $request->validated() );
    }

    function update( Request $request, Provider $provider, $provider_id ){
        $validate   = Validator::make( $request->all(), [
            'name'          => [ 'sometimes', 'unique:providers,name' ],
            'address'       => [ 'sometimes' , 'min:5' ],
            'description'   => [ 'sometimes' , 'min: 25' ],
            'geo_loc'       => [ 'sometimes', new LocationCoordinates ],
            'slug'          => [ 'unique:providers' ]
        ]);

        if( $validate->fails() ){
            return $this->error( 'api/providers.update.failed', $validate->errors() );
        }

        // fetch provider to update
        $providers  = $provider->where( 'id', $provider_id )->get();
        if( $providers->isEmpty() ){
            return $this->error( 'api/general.unauthorized' );
        }

        $provider   = $providers->first();
        if( utils()->user()->cannot( 'update', $provider ) ) {
            return $this->forbidden();
        }

        // get geolocation data if needed
        if( $request->has('geo_loc') ){
            list( $lat, $lng )  = explode( ',', $request->input('geo_loc') );
            $address            = utils()->getAddressByCoords( $lat, $lng, true );
            if( !empty( $address ) ){
                $request->merge( $address );
            }
        }

        if( $provider->update( $request->all()) ){
            return $this->success( 'api/providers.update.success' );
        }

        return $this->error( 'api/providers.update.failed' );
    }

    function store_images( Request $request, Provider $providers, Image $image, $provider_id, $images_type ){
        $validate   = Validator::make( [
            'images'        => $request->file('images'),
            'type'          => $images_type,
            'provider_id'   => $provider_id
        ],[
            'images.*'      => [ 'required', File::image()->max(10000) ],
            'type'          => [ 'required', 'in:cover,gallery'],
            'provider_id'   => [ 'required', 'exists:providers,id']
        ]);


        if( $validate->fails() || $request->file('images') == null ){
            return $this->error( 'api/images.upload.failed', $validate->errors() );
        }

        // store image(s) and add db record
        $provider   = $providers->where( 'id', $provider_id )->get()->first();
        $uploaded   = [];
        $files      = $images_type == 'cover'
                        ? [ $request->file( 'images')[array_key_first($request->file( 'images'))] ]
                        : $request->file('images');

        foreach( $files as $file ){
            if( $file->isValid() ){
                // If file already exists, link it to another imageable as well.
                $fileHash       = md5_file( $file->path() );
                $existing       = $image->hashExists( $fileHash );
                $path           = $existing === false
                                    ? $file->storePublicly( $images_type )
                                    : $existing->path;

                $upload         = $provider->images()->create([
                                    'name'      => utils()->slug( $file->getClientOriginalName() ),
                                    'path'      => $path,
                                    'ext'       => $file->extension(),
                                    'user_id'   => utils()->user()->id,
                                    'hash'      => $fileHash,
                                    'type'      => $images_type
                                ]);

                if( $images_type == 'cover' ){
                    $previousCovers = $provider->cover()->where( 'id', '!=', $upload->id )->get();
                    if( $previousCovers->isNotEmpty() ){
                        $previousCovers->each( function( $cover ){
                            $cover->delete();
                        });
                    }
                }

                $uploaded[]     = $upload;
            }
        }

        if( !empty( $uploaded ) ){
            return $this->created( trans_choice('api/images.upload.success', count( $uploaded ) ), $uploaded );
        }

        return $this->error( 'api/images.upload.failed' );
    }
}
