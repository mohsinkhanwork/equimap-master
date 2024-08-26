<?php

namespace App\Http\Controllers\Web;

use App\Actions\ValidatePermission;
use App\DataTables\PageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Page\PageUpdateRequest;
use App\Http\Requests\Page\PageStoreRequest;
use App\Models\Page;

class PageController extends Controller {
    public function show( Page $pages, $page_slug ){
        $page   = $pages
                    ->where( 'active', 1 )
                    ->where( 'slug', $page_slug )
                    ->get();
        if( $page->isNotEmpty() ){
            return view('pages.pages.show', [ 'page' => $page->first() ] );
        }

        return $this->viewError('errors.404', null );
    }

    public function index( PageDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Page::class );

        addJavascriptFile('assets/js/pages/page.listing.js');
        return $dataTable->render('pages.pages.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( Page::class );
        addJavascriptFile('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js');
        addJavascriptFile('assets/js/pages/page.create.js');

        return view('pages.pages.create');
    }

    public function store( PageStoreRequest $pageStoreRequest, Page $pages ){
        ValidatePermission::authorizeWeb( Page::class, 'create' );

        $store      = $pages->create( $pageStoreRequest->validated() );
        return $store->id > 0
                    ? $this->success( 'api/page.create.success', $store )
                    : $this->error( 'api/page.create.failed' );
    }

    public function edit( Page $pages, $page_id ){
        $page       = $pages->find( $page_id );
        ValidatePermission::authorizeWeb( $page );
        addJavascriptFile('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js');
        addJavascriptFile('assets/js/pages/page.create.js');

        return view('pages.pages.edit', [
            'page'     => $page
        ]);
    }

    public function update( PageUpdateRequest $pageUpdateRequest, Page $pages, $page_id ){
        $page       = $pages->find( $page_id );
        ValidatePermission::authorizeWeb( $page, 'edit' );

        return $page->update( $pageUpdateRequest->validated() )
                    ? $this->success( 'api/page.update.success' )
                    : $this->error( 'api/page.update.failed' );
    }

    public function destroy( Page $pages, $page_id ){
        $page       = $pages->find( $page_id );
        ValidatePermission::authorizeWeb( $page, 'delete' );

        return $page->delete()
                    ? $this->success( 'api/page.delete.success' )
                    : $this->error( 'api/page.delete.failed' );
    }
}
