<?php


namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\Http\Controllers\Controller;
use App\DataTables\HorseDataTable;
use App\Http\Requests\Horse\HorseStoreRequest;
use App\Http\Requests\Horse\HorseUpdateRequest;
use App\Models\Horse;
use App\Models\Provider;

class HorseController extends Controller {
    public function index( HorseDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Horse::class );
        addJavascriptFile('assets/js/pages/horse.listing.js');

        return $dataTable->render('pages.horses.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( Horse::class );
        addJavascriptFile('assets/js/pages/horse.create.js');

        return view('pages.horses.create', [
            'providers' => Provider::all(),
            'levels'    => Horse::getLevels(),
            'genders'   => Horse::getGenders()
        ]);
    }

    public function store( HorseStoreRequest $request, Horse $horses ){
        ValidatePermission::authorizeWeb( Horse::class, 'create' );

        $store      = $horses->create( $request->validated() );
        if( $store->id > 0 && $request->has('image') ){
            $store->uploadImage( $request->file('image') );
        }

        return $store->id > 0
                    ? $this->success( 'api/horse.create.success', $store )
                    : $this->error( 'api/horse.create.failed' );
    }

    public function edit( Horse $horses, $horse_id ){
        $horse      = $horses->find( $horse_id );
        ValidatePermission::authorizeWeb( $horse );
        addJavascriptFile('assets/js/pages/horse.create.js');

        return view('pages.horses.edit', [
            'horse'     => $horse,
            'levels'    => Horse::getLevels(),
            'genders'   => Horse::getGenders(),
            'providers' => Provider::all()
        ]);
    }

    public function update( HorseUpdateRequest $request, Horse $horses, $horse_id ){
        $horse      = $horses->find( $horse_id );
        ValidatePermission::authorizeWeb( $horse, 'edit' );

        $updated    = $horse->update( $request->validated() );
        if( $updated && $request->has('image') ){
            $horse->uploadImage( $request->file('image') );
        }

        return $updated
                ? $this->success( 'api/horse.update.success' )
                : $this->error( 'api/horse.update.failed' );
    }

    public function destroy( Horse $horses, $horse_id ){
        $horse    = $horses->find( $horse_id );
        ValidatePermission::authorizeWeb( $horse, 'delete' );

       return $horse->delete()
                    ? $this->success( 'api/horse.delete.success' )
                    : $this->error( 'api/horse.delete.failed' );
    }
}
