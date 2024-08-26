<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Package extends Model {
    const PACKAGE_TYPES     = [
        'service'       => 'Service',
        /*'travel'        => 'Travel',
        'competition'   => 'Competition'*/
    ];

    const INSTANCE_MAP      = [
        'service'       => 'service',
        'trip'          => 'trip',
        'travel'        => 'trip',
        'competition'   => 'trip',
    ];

    protected $fillable     = [
        'active',
        'approved',
        'sort',
        'name',
        'price',
        'quantity',
        'packageable_id',
        'packageable_type',
        'notes'
    ];
    protected $casts        = [
        'created_at'    => 'date:d-m-Y H:i:s',
        'updated_at'    => 'date:d-m-Y H:i:s',
    ];
    protected $hidden       = [
        'packageable_type',
        'packageable_id',
        'laravel_through_key'
    ];
    protected $appends  = [ 'provider_name', 'package_type' ];

    protected static function booted(){
        parent::boot();

        static::addGlobalScope('user_id', function ( Builder $builder ) {
            if( utils()->isProvider() ){
                /**
                 * This works here because service already has global scope
                 * to remove all services not belonging to current provider.
                 */
                $builder->whereHas( 'packageable' );
            }
        });
    }

    public function getProviderNameAttribute(){
        return $this->packageable()->get()->first()->provider_name;
    }

    public function getPackageTypeAttribute(){
        $package_type   = utils()->getMorphableName( $this->packageable_type );
        if( $package_type == 'trip' ){
            $package_type   = $this->packageable()->get()->first()->type;
        }

        return utils()->capitalize( $package_type );
    }

    public static function getDefaultPackageType(){
        return config('general.default_package_type' );
    }

    public static function getPackageTypes(){
        return self::PACKAGE_TYPES;
    }

    public static function getPackageableList( $type, $useMap=true ){
        // make sure its allowed type
        if( !isset( self::PACKAGE_TYPES[$type] ) || ( $useMap == true && !isset( self::INSTANCE_MAP[$type] ) ) ){
            return false;
        }

        $instance   = self::getPackageableInstance( $type );
        if( !$instance ){
            return false;
        }

        switch( $type ){
            case 'service':
                return $instance->all();
            break;
            case 'travel':
                return $instance->type('travel')->active()->get();
            break;
            case 'competition':
                return $instance->type('competition')->active()->get();
            break;
        }
    }

    public static function getPackageableInstance( $type ){
        $instanceType   = self::INSTANCE_MAP[$type];
        $instanceName   = "App\Models\\" . ucwords( utils()->singular( $instanceType ) );
        if( class_exists( $instanceName ) ){
            return new $instanceName;
        }
    }

    public function scopeActive( $query ){
        return $query->where([
            'packages.active'    => 1,
            'packages.approved'  => 1
        ]);
    }

    public function scopeType( $query, $type ){
        return $query->where( 'packageable_type', "App\\Models\\" . ucfirst( $type ) );
    }

    public function getPackageable(){
        $packageable    = $this->packageable()->get();
        return $packageable->isNotEmpty() ? $packageable->first() : false;
    }

    public function packageable(){
        return $this->morphTo();
    }
}
