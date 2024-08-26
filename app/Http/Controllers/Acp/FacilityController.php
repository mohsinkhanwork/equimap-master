<?php


namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\FacilitiesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Facility\FacilityStoreRequest;
use App\Http\Requests\Facility\FacilityUpdateRequest;
use App\Models\Facility;

class FacilityController extends Controller {
    public function index( FacilitiesDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Facility::class );
        addJavascriptFile('assets/js/pages/facility.listing.js');

        return $dataTable->render('pages.facilities.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( Facility::class );
        addJavascriptFile('assets/js/pages/facility.create.js');

        return view('pages.facilities.create');
    }

    public function store( FacilityStoreRequest $request, Facility $facility ){
        ValidatePermission::authorizeWeb( Facility::class, 'create' );

        $store  = $facility->create( $request->validated() );
        if( $store->id > 0 && $request->has('icon') ){
            $store->uploadIcon( $request->file('icon') );
        }

        return $store->id > 0
                ? $this->success( 'api/facility.create.success', $store )
                : $this->error( 'api/facility.create.failed' );
    }

    public function edit( Facility $facilities, $facility_id ){
        $facility   = $facilities->find( $facility_id );
        ValidatePermission::authorizeWeb( $facility );
        addJavascriptFile('assets/js/pages/facility.create.js');

        return view('pages.facilities.edit', [
            'facility'  => $facility
        ]);
    }

    public function update( FacilityUpdateRequest $request, Facility $facilities, $facility_id ){
        $facility   = $facilities->find( $facility_id );
        ValidatePermission::authorizeWeb( $facility, 'edit' );

        $updated    = $facility->update( $request->validated() );
        if( $updated && $request->has('icon') ){
            $facility->uploadIcon( $request->file('icon') );
        }

        return $updated
                    ? $this->success( 'api/facility.update.success' )
                    : $this->error( 'api/facility.update.failed' );
    }

    public function destroy( Facility $facilities, $facility_id ){
        $facility   = $facilities->find( $facility_id );
        ValidatePermission::authorizeWeb( $facility, 'delete' );

        return $facility->first()->delete()
            ? $this->success( 'api/facility.delete.success' )
            : $this->error( 'api/facility.delete.failed' );
    }
}
