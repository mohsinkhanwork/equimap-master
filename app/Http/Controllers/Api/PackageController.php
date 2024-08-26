<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Package\PackageIndexRequest;
use App\Models\Package;

class PackageController extends Controller {
    function index( PackageIndexRequest $request, Package $package ){
        $packages   = $package
                        ->active()
                        ->type( $request->type )
                        ->orderByDesc('sort')
                        ->paginate();

        if( $packages->isNotEmpty() ){
            return $this->success( 'api/package.index.success', $packages );
        }

        return $this->notfound( 'api/package.index.no_results' );
    }

    function show( Package $package, $package_id ){
        $packages   = $package
                        ->where( 'id', $package_id )
                        ->active()
                        ->with( [ 'packageable' ] )
                        ->get();

        if( $packages->isNotEmpty() ){
            $packages   = $packages->first();
            return $this->success( 'api/package.index.success', $packages );
        }

        return $this->notfound( 'api/package.index.no_results' );
    }
}
