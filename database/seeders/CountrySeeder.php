<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $rawCountries   = File::get(storage_path('app/countries.json'));
        $listCountries  = json_decode( $rawCountries, true );

        foreach( $listCountries as $code => $country ){
            Country::updateOrInsert(['code' => $code], [
                'code' => $country['code'],
                'name' => $country['name'],
                'dialing_code' => $country['dialing_code'],
                'currency' => $country['currency']
            ]);
        }
    }
}
