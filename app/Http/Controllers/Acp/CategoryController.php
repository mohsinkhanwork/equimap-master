<?php


namespace App\Http\Controllers\Acp;

use App\Actions\ValidatePermission;
use App\DataTables\CategoriesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;

class CategoryController extends Controller {
    public function index( CategoriesDataTable $dataTable ){
        ValidatePermission::authorizeWeb( Category::class );
        addJavascriptFile('assets/js/pages/category.listing.js');

        return $dataTable->render('pages.categories.index');
    }

    public function create(){
        ValidatePermission::authorizeWeb( Category::class );
        addJavascriptFile('assets/js/pages/category.create.js');

        return view('pages.categories.create');
    }

    public function store( CategoryStoreRequest $request, Category $category ){
        ValidatePermission::authorizeWeb( Category::class, 'create' );

        $store  = $category->create( $request->validated() );
        if( $store->id > 0 && $request->has('icon') ){
            $store->uploadIcon( $request->file('icon') );
        }

        return $store->id > 0
                    ? $this->success( 'api/category.create.success', $store )
                    : $this->error( 'api/category.create.failed' );
    }

    public function edit( Category $categories, $category_id ){
        $category   = $categories->find( $category_id );
        addJavascriptFile('assets/js/pages/category.create.js');

        return view('pages.categories.edit', [ 'category' => $category ] );
    }

    public function update( CategoryUpdateRequest $request, Category $categories, $category_id ){
        $category   = $categories->find( $category_id );
        ValidatePermission::authorizeWeb( $category, 'edit' );

        $updated    = $category->update( $request->validated() );
        if( $updated && $request->has('icon') ){
            $category->uploadIcon( $request->file('icon') );
        }

        return $updated
                        ? $this->success( 'api/category.update.success' )
                        : $this->error( 'api/category.update.failed' );
    }

    public function destroy( Category $categories, $category_id ){
        $category   = $categories->find( $category_id );
        ValidatePermission::authorizeWeb( $category );

        return $category->delete()
                ? $this->success( 'api/category.delete.success' )
                : $this->error( 'api/category.delete.failed' );
    }
}
