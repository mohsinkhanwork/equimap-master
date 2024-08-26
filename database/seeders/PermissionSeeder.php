<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder{
    public function run(){
        // First create permissions
        $permissions  = [
            // dashboard
            'view dashboard',

            // categories
            'view category',
            'create category',
            'edit category',
            'delete category',

            // facilities
            'view facility',
            'create facility',
            'edit facility',
            'delete facility',

            // providers
            'view provider',
            'create provider',
            'edit provider',
            'edit provider featured',
            'delete provider',
            'delete provider images',

            // services
            'view service',
            'create service',
            'edit service',
            'delete service',
            'approve service',

            // horse
            'view horse',
            'create horse',
            'edit horse',
            'delete horse',

            // trainer
            'view trainer',
            'create trainer',
            'edit trainer',
            'delete trainer',

            // trips
            'view trip',
            'create trip',
            'edit trip',
            'delete trip',
            'update trip images',
            'delete trip images',
            'approve trip',

            // courses
            'view course',
            'create course',
            'edit course',
            'delete course',
            'update course images',
            'delete course images',
            'approve course',

            // packages
            'view package',
            'create package',
            'edit package',
            'delete package',
            'approve package',

            // user
            'view user',
            'create user',
            'edit user',
            'delete user',
            'verify user',
            'assign user role',

            // booking
            'view booking',
            'create booking',
            'edit booking',
            'delete booking',

            // pages
            'view page',
            'create page',
            'edit page',
            'delete page',

            // banners
            'view banner',
            'create banner',
            'edit banner',
            'delete banner'
        ];

        // create permissions
        foreach( $permissions as $permissionName ){
            $permission = Permission::where( 'name', $permissionName )->get();
            if( $permission->isEmpty() ){
                $permission = Permission::create( [ 'name' => $permissionName ] );
            }
            else{
                $permission = $permission->first();
            }

        }

        // Create Roles
        $roles  = [
            'super admin'   => [
                // dashboard
                'view dashboard',

                // categories
                'view category',
                'create category',
                'edit category',
                'delete category',

                // facilities
                'view facility',
                'create facility',
                'edit facility',
                'delete facility',

                // providers
                'view provider',
                'create provider',
                'edit provider',
                'edit provider featured',
                'delete provider',
                'delete provider images',

                // services
                'view service',
                'create service',
                'edit service',
                'delete service',
                'approve service',

                // horse
                'view horse',
                'create horse',
                'edit horse',
                'delete horse',

                // trainer
                'view trainer',
                'create trainer',
                'edit trainer',
                'delete trainer',

                // trip
                'view trip',
                'create trip',
                'edit trip',
                'delete trip',
                'update trip images',
                'delete trip images',
                'approve trip',

                // courses
                'view course',
                'create course',
                'edit course',
                'delete course',
                'update course images',
                'delete course images',
                'approve course',

                // packages
                'view package',
                'create package',
                'edit package',
                'delete package',
                'approve package',

                // user
                'view user',
                'create user',
                'edit user',
                'delete user',
                'verify user',
                'assign user role',

                // booking
                'view booking',
                'edit booking',

                // pages
                'view page',
                'create page',
                'edit page',
                'delete page',

                // banners
                'view banner',
                'create banner',
                'edit banner',
                'delete banner',
            ],
            'admin'         => [
                // dashboard
                'view dashboard',

                // categories
                'view category',
                'create category',
                'edit category',

                // facilities
                'view facility',
                'create facility',
                'edit facility',

                // providers
                'view provider',
                'create provider',
                'edit provider featured',
                'edit provider',
                'delete provider images',

                // services
                'view service',
                'create service',
                'edit service',
                'approve service',

                // horse
                'view horse',
                'create horse',
                'edit horse',

                // trainer
                'view trainer',
                'create trainer',
                'edit trainer',

                // trips
                'view trip',
                'create trip',
                'edit trip',
                'update trip images',
                'approve trip',

                // courses
                'view course',
                'create course',
                'edit course',
                'update course images',
                'approve course',

                // packages
                'view package',
                'create package',
                'edit package',
                'approve package',

                // user
                'view user',
                'create user',
                'edit user',
                'verify user',
                'assign user role',

                // booking
                'view booking',
                'edit booking',

                // pages
                'view page',
                'create page',
                'edit page',

                // banners
                'view banner',
                'create banner',
                'edit banner',
            ],
            'manager'       => [
                // categories
                'view category',
                'edit category',

                // facilities
                'view facility',
                'edit facility',

                // provider
                'view provider',
                'edit provider',

                // services
                'view service',
                'edit service',

                // horse
                'view horse',
                'edit horse',

                // trainer
                'view trainer',
                'edit trainer',

                // trips
                'view trip',
                'edit trip',

                // courses
                'view course',
                'edit course',

                // packages
                'view package',
                'edit package',

                // user
                'view user',

                // booking
                'view booking',

                // pages
                'view page',

                // banners
                'view banner',
                'edit banner',
            ],
            'vendor'        => [
                // dashboard
                'view dashboard',

                // providers
                'view provider',
                'create provider',
                'edit provider',
                'delete provider images',

                // services
                'view service',
                'create service',
                'edit service',

                // horse
                'view horse',
                'create horse',
                'edit horse',
                'delete horse',

                // trainer
                'view trainer',
                'create trainer',
                'edit trainer',
                'delete trainer',

                // trips
                'view trip',
                'create trip',
                'edit trip',
                'update trip images',
                'delete trip images',

                // courses
                'view course',
                'create course',
                'edit course',
                'update course images',
                'delete course images',

                // packages
                'view package',
                'create package',
                'edit package',

                // booking
                'view booking',
                'edit booking',
            ],
            'customer'      => [
                'view provider',
                'view service',
                'view horse',
                'view trainer',
                'view trip',
                'view course',
                'view booking',
                'view page',
                'view package',
            ]
        ];

        foreach( $roles as $roleName => $permissions ){
            $role = Role::where( 'name', $roleName )->get();
            if( $role->isEmpty() ){
                $role   = Role::create( ['name' => $roleName ] );
            }
            else{
                $role   = $role->first();
            }

            foreach( $permissions as $permission ){
                $role->givePermissionTo( $permission );
            }
        }
    }
}
