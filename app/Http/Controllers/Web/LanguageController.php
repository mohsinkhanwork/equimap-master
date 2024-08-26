<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller {
    public function change( Request $request ){
        if( $request->has('locale') && app()->getLocale() != $request->input('locale') ){
            app()->setLocale( $request->input('locale') );
            session( [ 'locale' => $request->input('locale') ] );
            return redirect()->back();
        }
    }
}
