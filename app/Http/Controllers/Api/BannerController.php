<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller{
    public function index( Banner $banner ){
        $banners   = $banner
                        ->whereHas('image')
                        ->where( 'active', 1 )
                        ->orderByDesc('sort')
                        ->paginate();

        if( $banners->isNotEmpty() ){
            return $this->success( 'api/banner.read.success', $banners );
        }

        return $this->notfound( 'api/banner.read.not_found' );
    }
}
