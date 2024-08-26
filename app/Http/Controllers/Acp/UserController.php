<?php


namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\User\UserVerifyManualRequest;
use App\Models\Country;
use App\Models\User;
use App\Services\LoginUserService;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\Permission\Models\Role;

class UserController extends Controller {
    public function index( UserDataTable $dataTable ){
        ValidatePermission::authorizeWeb( User::class );
        addJavascriptFile('assets/js/pages/user.listing.js');

        return $dataTable->render('pages.users.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( User::class );
        addJavascriptFile('assets/js/pages/user.create.js');

        return view('pages.users.create', [
            'countries' => Country::all(),
            'roles'     => auth()->user()->hasRole('super admin') ? Role::all() : Role::all()->whereNotIn('name', ['super admin'] )
        ]);
    }

    public function store( UserStoreRequest $userStoreRequest, User $users ){
        ValidatePermission::authorizeWeb( User::class, 'create' );

        $store      = $users->create( $userStoreRequest->validated() );
        if( $store->id > 0 && $userStoreRequest->has('roles') ){
            $store->assignRole( $userStoreRequest->roles );
        }

        return $store->id > 0
                    ? $this->success( 'api/user.create.success', $store )
                    : $this->error( 'api/user.create.failed' );
    }

    public function edit( User $users, $user_id ){
        $user       = $users->with([ 'roles' ])->find( $user_id );
        ValidatePermission::authorizeWeb( $user );
        addJavascriptFile('assets/js/pages/user.create.js');

        return view('pages.users.edit', [
            'user'      => $user,
            'countries' => Country::all(),
            'roles'     => auth()->user()->hasRole('super admin') ? Role::all() : Role::all()->whereNotIn('name', ['super admin'] )
        ]);
    }

    public function update( UserUpdateRequest $userUpdateRequest, User $users, $user_id ){
        $user       = $users->find( $user_id );
        ValidatePermission::authorizeWeb( $user, 'edit' );

        $updated    = $user->update( $userUpdateRequest->validated() );
        if( $updated && $userUpdateRequest->has('roles') ){
            // for first user avoid deleting super-admin
            $roles  = $userUpdateRequest->roles;
            if( $user->id == config('general.permanent_super_admin' ) ){
                $roles[]    = 'super admin';
            }

            $user->syncRoles( array_unique( $roles ) );
        }

        return $user->update( $userUpdateRequest->validated() )
                    ? $this->success( 'api/user.update.success' )
                    : $this->error( 'api/user.update.failed' );
    }

    public function verify( UserVerifyManualRequest $userVerifyManualRequest, User $users, LoginUserService $loginUserService, $user_id ){
        $user       = $users->find( $user_id );
        ValidatePermission::authorizeWeb( $user, 'verify' );

        $updated    = $loginUserService->setUser( $user )->setVerified();
        return $updated
                    ? $this->success( 'api/user.verification.success' )
                    : $this->error( 'api/user.verification.failed' );
    }

    public function destroy( User $users, $user_id ){
        $user       = $users->find( $user_id );
        ValidatePermission::authorizeWeb( $user, 'delete' );

        return $user->id != config('general.permanent_super_admin') && $user->safeDelete()
                    ? $this->success( 'api/user.delete.success' )
                    : $this->error( 'api/user.delete.failed' );
    }
}
