<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryIconUploadRequest;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class CategoryController extends Controller {
    function index( Category $category ){
        $categories = $category
                        ->orderByDesc('sort')
                        ->paginate();

        if( $categories->isNotEmpty() ){
            return $this->success( 'api/category.index.success', $categories );
        }

        return $this->notfound( 'api/category.index.no_results' );
    }

    public function store( Request $request, Category $category ){
        $validate   = Validator::make( $request->all(), [
            'name'  => [ 'required', 'string', 'min: 5', 'unique:categories,name' ],
            'sort'  => [ 'numeric', 'gte:0' ]
        ]);

        if( $validate->fails() ){
            return $this->error( 'api/categories.create.failed', $validate->errors() );
        }

        return $category->createAndReturn(
            $request->all(),
            'api/categories.create.success',
            'api/categories.create.failed'
        );
    }

    function store_icon( CategoryIconUploadRequest $request, Category $categories, $category_id ){
        $category   = $categories->where( 'id', $category_id )->get()->first();
        $icon       = $category->uploadIcon( $request );

        if( !empty( $icon ) ){
            return $this->created( 'api/category.icon.success', $icon );
        }

        return $this->error( 'api/images.icon.failed' );
    }

    function services( Category $category, $category_id ){
        $categories = $category
                        ->where( 'id', $category_id )
                        ->get();

        if( $categories->isNotEmpty() ){
            $services   = $categories
                            ->first()
                            ->services()
                            ->paginate();

            if( $services->isNotEmpty() ){
                return $this->success( 'api/category.services.success', $services );
            }
        }

        return $this->notfound( 'api/category.services.no_results' );
    }

    function providers( Category $category, $category_id ){
        $categories = $category
                        ->where( 'id', $category_id )
                        ->get();

        if( $categories->isNotEmpty() ){
            $providers  = $categories
                            ->first()
                            ->enabledServices()
                            ->distinct('providers.id')
                            ->orderBy('providers.id')
                            ->paginate();

            if( $providers->isNotEmpty() ){
                return $this->success( 'api/category.providers.success', $providers );
            }
        }

        return $this->notfound( 'api/category.providers.no_results' );
    }
}
