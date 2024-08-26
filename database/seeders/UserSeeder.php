<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder {
    use WithoutModelEvents;

    /**
     * Seed the application's user table.
     *
     * @return void
     */
    public function run(){
        // create admin user
        $login  = "+971506558420";
        if( User::where( 'login', $login )->get()->isEmpty() ){
            $user       = User::create([
                'active'            => 1,
                'name'              => 'Bilal Shahwani',
                'login'             => $login,
                'login_verified_at' => utils()->currentTime(),
                'password'          => env('SUPER_ADMIN_SEED_PASSWORD')
            ]);

            $profile    = $user->profile()->create([
                    'name'      => 'Bilal Shahwani'
            ]);

            $user->assignRole('super admin');
        }
    }
}
