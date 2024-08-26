<?php

namespace App\Http\Controllers\Web;

use App\Actions\ValidatePermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDeleteRequest;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
    public function delete(){
        addJavascriptFile('assets/js/web/users.delete.js');

        return view('web.users.delete', [
            'countries' => Country::all()
        ]);
    }

    public function destroy( UserDeleteRequest $request, User $users ){
        $deleted    = false;
        if( Auth::attempt([
            'login' => $request->login,
            'password' => $request->password
        ])){
            $deleted = $users->find( auth()->id() )->delete();
        }

        return $deleted
                    ? $this->success( 'web/users.delete.success' )
                    : $this->error( 'web/users.delete.failed' );
    }
}
