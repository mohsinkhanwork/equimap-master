<?php

namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerStoreRequest;
use App\Http\Requests\Banner\BannerUpdateRequest;
use App\Http\Requests\Page\PageUpdateRequest;
use App\Http\Requests\Page\PageStoreRequest;
use App\Models\Banner;

class BannerController extends Controller {
    public function index( BannerDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Banner::class );
        addJavascriptFile('assets/js/pages/banner.listing.js');

        return $dataTable->render('pages.banners.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( Banner::class );
        addJavascriptFile('assets/js/pages/banner.create.js');

        return view('pages.banners.create', [
            'types'     => Banner::getTypes(),
            'entities'  => Banner::getEntities()
        ]);
    }

    public function store( BannerStoreRequest $bannerStoreRequest, Banner $banners ){
        ValidatePermission::authorizeWeb( Banner::class, 'create' );

        $store      = $banners->create( $bannerStoreRequest->validated() );
        if( $store->id > 0 && $bannerStoreRequest->has('image') ){
            $store->uploadImage( $bannerStoreRequest->file('image') );
        }

        return $store->id > 0
                    ? $this->success( 'api/banner.create.success', $store )
                    : $this->error( 'api/banner.create.failed' );
    }

    public function edit( Banner $banners, $banner_id ){
        $banner     = $banners->find( $banner_id );
        ValidatePermission::authorizeWeb( $banner );
        addJavascriptFile('assets/js/pages/banner.create.js');

        return view('pages.banners.edit', [
            'banner'    => $banner,
            'types'     => Banner::getTypes(),
            'entities'  => Banner::getEntities()
        ]);
    }

    public function update( BannerUpdateRequest $bannerUpdateRequest, Banner $banners, $banner_id ){
        $banner     = $banners->find( $banner_id );
        ValidatePermission::authorizeWeb( $banner, 'edit' );

        $updated    = $banner->update( $bannerUpdateRequest->validated() );
        if( $updated && $bannerUpdateRequest->has('image') ){
            $banner->uploadImage( $bannerUpdateRequest->file('image') );
        }

        return $updated
                    ? $this->success( 'api/banner.update.success' )
                    : $this->error( 'api/banner.update.failed' );
    }

    public function destroy( Banner $banners, $banner_id ){
        $banner     = $banners->find( $banner_id );
        ValidatePermission::authorizeWeb( $banner, 'delete' );

        return $banner->delete()
                    ? $this->success( 'api/banner.delete.success' )
                    : $this->error( 'api/banner.delete.failed' );
    }
}
