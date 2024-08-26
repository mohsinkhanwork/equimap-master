<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ProviderController extends Controller {
    public function show( $provider_slug, $provider_id ){
        return redirect(env('APP_ONE_LINK'));
    }
}
