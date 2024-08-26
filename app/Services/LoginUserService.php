<?php

namespace App\Services;

use App\Actions\StopExecutionAction;
use App\Models\User;
use Exception;
use Illuminate\Support\Str;

class LoginUserService{
    protected $user;

    public function user(){
        if( empty( $this->user ) || is_null( $this->user ) ){
            throw new Exception( 'Failed to setup login service, invalid login/password.');
        }

        return $this->user;
    }

    public function setUser( User $user ){
        $this->user = $user;
        return $this;
    }

    public function getSafeUser(){
        return $this->user()->makeHidden( ['roles', 'permissions', 'stripe_id', 'remember_token'] );
    }

    protected function userAbilities(){
        return $this->user()->getAllPermissions()->pluck('name')->toArray();
    }

    protected function createToken( $tokenName ){
        return $this->user()->createToken( $tokenName, $this->userAbilities() )->plainTextToken;
    }

    public function setup( $login, $password ){
        $user   = User::where( 'login', $login )->get();
        if( $user->isNotEmpty() && $this->verifyPassword( [ 'login' => $login, 'password' => $password ] ) ){
            $this->user = $user->first();
            return $this;
        }

        StopExecutionAction::handle( 'api/user.login.invalid_credentials', 400 );
    }

    public function isActiveUser(){
        return $this->user()->active;
    }

    public function isVerifiedUser(){
        return !is_null( $this->user()->login_verified_at );
    }

    public function setVerified(){
        $this->user()->update( [ 'active' => true, 'login_verified_at' => utils()->currentTime() ] );
        return $this;
    }

    public function verifyPassword( $credentials ){
        return auth()->validate( $credentials );
    }

    public function loginApi( $tokenName='mobile-app', $deviceToken=null ){
        $this->user()->token    = $this->createToken( $tokenName );
        if( $deviceToken !== null ){
            $this->saveDevice( $deviceToken );
        }

        return $this;
    }

    public function loginWeb(){
        return auth()->guard('web')->loginUsingId( $this->user()->id, true );
    }

    public function saveDevice( $token ){
        if( $token && utils()->validateFcmToken( $token ) ){
            $this->user()->devices()->updateOrCreate( ['token' => $token ] );
        }

        return $this;
    }

    public function setVerificationToken(){
        $token  = Str::random(32);
        $this->user()->update( ['remember_token' => $token ] );

        return $token;
    }
}
