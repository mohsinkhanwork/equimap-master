<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class FacilityController extends Controller {
    function index( Facility $facility ){
        $facilities = $facility
                        ->paginate();

        if( $facilities->isNotEmpty() ){
            return $this->success( 'api/facilities.list.success', $facilities );
        }

        return $this->notfound( 'api/facilities.list.no_results' );
    }

    public function store( Request $request, Facility $facilities ){
        $validate   = Validator::make( $request->all(), [
            'name'  => [ 'required', 'min:3', 'unique:facilities,name' ],
            'sort'  => [ 'numeric', 'gte:0' ],
            'icon'  => [ 'required', File::image()->max( 2000 ) ]
        ]);

        if( $validate->fails() ){
            return $this->error( 'api/facilities.create.failed', $validate->errors() );
        }

        $facility   = $facilities->create( $request->all() );
        if( $facility->id > 0 ){
            $image      = $request->file( 'icon' );
            $fileHash   = md5_file( $image->path() );
            $path       = $image->storePublicly( 'icons' );

            $facility->icon()->create([
                'name'      => utils()->slug( $image->getClientOriginalName() ),
                'path'      => $path,
                'ext'       => $image->extension(),
                'user_id'   => utils()->user()->id,
                'hash'      => $fileHash,
                'type'      => 'icon'
            ]);

            return $this->created( 'api/facilities.create.success', $facility );
        }

        return $this->error( 'api/facilities.create.failed' );
    }
}
