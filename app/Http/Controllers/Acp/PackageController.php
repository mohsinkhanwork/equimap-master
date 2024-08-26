<?php


namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\PackageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Package\PackageStoreRequest;
use App\Http\Requests\Package\PackageUpdateRequest;
use App\Models\Package;

class PackageController extends Controller {
    public function index( PackageDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Package::class );
        addJavascriptFile('assets/js/pages/package.listing.js');

        return $dataTable->render('pages.packages.index' );
    }


    public function create(){
        ValidatePermission::authorizeWeb( Package::class );
        addJavascriptFile('assets/js/pages/package.create.js');

        // base parameters
        $package_type       = request()->package_type;
        $package_types      = Package::getPackageTypes();

        // we should not proceed without packageble
        if( !$package_type || !isset( $package_types[$package_type]) ){
            $error  = __('acp/error.message.incorrect_packageable');
            return view('pages.packages.create' )->with('error', $error );
        }

        // fetch packageble data
        $packagebles        = Package::getPackageableList( $package_type );
        return view('pages.packages.create', [
            'package_type'      => $package_type,
            'package_types'     => $package_types,
            'packageables'      => $packagebles
        ]);
    }

    public function store( PackageStoreRequest $request, Package $packages ){
        ValidatePermission::authorizeWeb( Package::class, 'create' );

        // get packageable instance
        $package_type   = $request->validated('packageable_type');
        $packageable_id = $request->validated('packageable_id');
        $packageable    = Package::getPackageableInstance( $package_type );
        $store          = $packageable->find( $packageable_id )->packages()->create( $request->validated() );
        return $store->id > 0
                    ? $this->success( 'api/package.create.success', $store )
                    : $this->error( 'api/package.create.failed' );
    }

    public function edit( Package $packages, $package_id ){
        $package    = $packages->find( $package_id );
        ValidatePermission::authorizeWeb( $package );
        addJavascriptFile('assets/js/pages/package.create.js');

        $package_type       = utils()->getMorphableName( $package->packageable_type );
        $package_types      = Package::getPackageTypes();

        return view('pages.packages.edit', [
            'package'           => $package,
            'package_type'      => $package_type,
            'package_types'     => $package_types
        ]);
    }

    public function update( PackageUpdateRequest $request, Package $packages, $package_id ){
        $package    = $packages->find( $package_id );
        ValidatePermission::authorizeWeb( $package, 'edit' );

        return $package->update( $request->validated() )
                    ? $this->success( 'api/package.update.success' )
                    : $this->error( 'api/package.update.failed' );
    }

    public function destroy( Package $packages, $package_id ){
        $package    = $packages->find( $package_id );
        ValidatePermission::authorizeWeb( $package, 'delete' );

        return $package->first()->delete()
                ? $this->success( 'api/package.delete.success' )
                : $this->error( 'api/package.delete.failed' );
    }
}
