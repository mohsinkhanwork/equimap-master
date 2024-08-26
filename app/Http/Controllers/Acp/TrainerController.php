<?php


namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\TrainerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\TrainerStoreRequest;
use App\Http\Requests\Trainer\TrainerUpdateRequest;
use App\Models\Provider;
use App\Models\Trainer;

class TrainerController extends Controller {
    public function index( TrainerDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Trainer::class );
        addJavascriptFile('assets/js/pages/trainer.listing.js');

        return $dataTable->render('pages.trainers.index' );
    }

    public function create(){
        ValidatePermission::authorizeWeb( Trainer::class );
        addJavascriptFile('assets/js/pages/trainer.create.js');

        return view('pages.trainers.create', [ 'providers' => Provider::all() ]);
    }

    public function store( TrainerStoreRequest $request, Trainer $trainers ){
        ValidatePermission::authorizeWeb( Trainer::class, 'create' );

        $store  = $trainers->create( $request->validated() );
        return $store->id > 0
                    ? $this->success( 'api/trainer.create.success', $store )
                    : $this->error( 'api/trainer.create.failed' );
    }

    public function edit( Trainer $trainers, $trainer_id ){
        $trainer    = $trainers->find( $trainer_id );
        ValidatePermission::authorizeWeb( $trainer );
        addJavascriptFile('assets/js/pages/trainer.create.js');

        return view('pages.trainers.edit', [
            'trainer'   => $trainer,
            'providers' => Provider::all()
        ]);
    }

    public function update( TrainerUpdateRequest $request, Trainer $trainers, $trainer_id ){
        $trainer    = $trainers->find( $trainer_id );
        ValidatePermission::authorizeWeb( $trainer, 'edit' );

        return $trainer->update( $request->validated() )
                    ? $this->success( 'api/trainer.update.success' )
                    : $this->error( 'api/trainer.update.failed' );
    }

    public function destroy( Trainer $trainers, $trainer_id ){
        $trainer    = $trainers->find( $trainer_id );
        ValidatePermission::authorizeWeb( $trainer, 'delete' );

        return $trainer->first()->delete()
                ? $this->success( 'api/trainer.delete.success' )
                : $this->error( 'api/trainer.delete.failed' );
    }
}
