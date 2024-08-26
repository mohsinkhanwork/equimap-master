<?php


namespace App\Http\Controllers\Acp;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserVerifyRequest;
use App\Http\Requests\User\VendorStoreRequest;
use App\Models\Country;
use App\Models\User;
use App\Services\LoginUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

class AuthController extends Controller{
    public function index(){
        addJavascriptFile('assets/js/pages/auth.login.js');

        return view('pages/auth.login', [
            'countries' => Country::all()->sortBy('name')
        ]);
    }

    public function login( UserLoginRequest $request, LoginUserService $loginUserService ){
        // setup login service
        $service    = $loginUserService->setup( $request->login, $request->password );

        // user is not available
        if( !$service ){
            return $this->error( 'api/user.login.invalid_credentials' );
        }

        // user must be verified
        if( !$service->isVerifiedUser() ){
            $redirectUrl    = route('acp.auth.verify', [ 'token' => $service->setVerificationToken() ] );
            return $this->redirect( 'api/user.login.unverified', [ 'redirect_url' => $redirectUrl ] );
        }

        // user must be active
        if( !$service->isActiveUser() ){
            return $this->error( 'api/user.login.disabled' );
        }

        // now that all is well lets authenticate
        $login  = $service->loginWeb();
        return $login
                    ? $this->success( 'api/user.login.success' )
                    : $this->error('api/user.login.failed', null, [ 'email' => config('api.contact_email')] );
    }

    public function logout( Request $request ){
        // logout and invalidate session
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // redirect to login screen
        return redirect( route('acp.auth.login') );
    }

    public function register(){
        addJavascriptFile('assets/js/pages/auth.create.js');

        return view('pages.auth.register', [
            'countries' => Country::all()->sortBy('name')
        ]);
    }

    public function store( VendorStoreRequest $request, User $user ){
        $store      = $user->create( $request->all() );
        if( $store->id > 0 ){
            $store->assignRole('vendor');
            $store->profile()->create( [ 'name' => $request->name ] );
        }

        return $store->id > 0
                    ? $this->success( 'api/user.create.unverified', [ 'redirect_url' => route('acp.auth.verify', [ 'token' => $store->remember_token ] ) ] )
                    : $this->error( 'api/user.create.failed' );
    }

    public function verify( Request $request, User $users ){
        // verify token is valid
        $phoneByHash    = $users->where( 'remember_token', $request->token )->get();
        if( $phoneByHash->isEmpty() ){
            return redirect( route('acp.auth.login' ) )->with( 'message', __('api/user.verification.token_invalid') );
        }

        // verify user is not already verified
        $user           = $phoneByHash->first();
        if( $user->isVerified() ){
            return redirect( route('acp.auth.login' ) )->with( 'message', __('api/user.verification.already_verified') );
        }

        addJavascriptFile('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
        addJavascriptFile('https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js');
        addJavascriptFile('assets/js/pages/auth.verify.js');

        $firebaseConfig = file_get_contents( base_path('config/credentials/firebase-config.json') );
        return view('pages.auth.verify', [ 'config' => $firebaseConfig, 'user' => $user ] );
    }

    public function verify_user( UserVerifyRequest $request, User $users ){
        $user   = $users->where( 'login', utils()->getPhoneFromFbaToken( $request->token ) )->get();
        if( $user->isNotEmpty() ){
            $user   = $user->first()->markVerified();

            auth()->guard('web')->login( $user, true );
            return $this->success( 'api/user.verification.success', $user );
        }

        return $this->error( 'api/user.verification.failed' );
    }
}
