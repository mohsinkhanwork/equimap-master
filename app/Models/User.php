<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\Sanctum;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\Permission\Traits\HasRoles;
use function Illuminate\Events\queueable;

/**
 * @property bool $active Denotes if user account is active or disabled.
 * @property string $name Name of the registered user.
 * @property string $login Phone or email of the registered user.
 * @property string $login_verified_at Time when user account was verified.
 * @property string $password Hashed password of the registered user.
 * @property string $stripe_id Customer ID in stripe payment gateway.
 * @property Sanctum $token Injected value of user token from PersonalAccessTokens (via Sanctum).
 */

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, Billable, HasRoles, SoftDeletes;

    public $timestamps      = true;
    protected $fillable     = [
        'active',
        'name',
        'login',
        'login_verified_at',
        'password',
        'remember_token'
    ];

    protected $hidden       = [
        'password',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'deleted_at'
    ];

    protected $dates        = [
        'deleted_at'
    ];

    protected $attributes   = [
        'active'    => false
    ];

    protected $casts        = [
        'login_verified_at' => 'date:d-m-Y H:i:s',
    ];

    protected $appends      = [
        'login_verified',
        'phone',
        'country'
    ];


    private $failed;
    private $failedReason;

    protected static function booted(){
        parent::boot();

        static::creating( function( $user ){
            $user->remember_token   = Str::random(32);
        });

        static::created( function( $user ){
            if( env('APP_ENV') == 'production' ){
                $user->createAsStripeCustomer([
                    'name'  => $user->name,
                    'phone' => $user->login
                ]);
            }
        });

        static::updated( queueable( function( $user ){
            if( $user->hasStripeId() && env('APP_ENV') == 'production'
                && ( in_array('name', $user->getChanges() ) || in_array('phone', $user->getChanges() ) ) ){
                $user->updateStripeCustomer([
                    'name'  => $user->name,
                    'phone' => $user->login
                ]);
            }
        }));
    }

    protected function setPasswordAttribute( $value ){
        $this->attributes['password'] = utils()->hashPassword( $value );
    }

    public function getLoginVerifiedAttribute(){
        return !is_null( $this->login_verified_at );
    }

    public function getPhoneAttribute(){
        return ( new PhoneNumber( $this->login ) );
    }

    public function getCountryAttribute(){
        return ( new Country() )->where('code', 'AE')->get()->first();
    }

    public function safeDelete(){
        // remove all PI data
        $updated    = $this->update([
            'active'            => 0,
            'login'             => Str::upper( Str::random(10)),
            'login_verified_at' => null,
        ]);

        // delete all api tokens
        $this->tokens()->delete();

        // remove all user roles
        $this->syncRoles([]);

        // soft delete user (important to keep db record for bookings, history etc. purpose)
        return $updated ? $this->delete() : null;
    }

    public function scopeActive( $query ){
        return $query->where( 'active', 1 );
    }

    public function createProfile(){
        return $this->profile()
            ->create([
                'name'  => $this->name
            ]);
    }

    public function markVerified(){
        $this->update([ 'active' => true, 'login_verified_at' => utils()->currentTime() ]);
        return $this;
    }

    public function updatePassword( $password ){
        $reset = $this->update([ 'password' => $password ]);
        if( $reset ){
            $this->tokens()->delete();
        }

        return $this;
    }

    public function isVerified(){
        return !is_null( $this->login_verified_at );
    }

    public function profile(){
        return $this->hasOne('App\Models\UsersProfile', 'user_id', 'id' );
    }

    public function devices(){
        return $this->hasMany( 'App\Models\UsersDevice', 'user_id', 'id' );
    }
}
