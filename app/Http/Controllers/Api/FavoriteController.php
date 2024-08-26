<?php


namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Favorite\FavoriteDeleteRequest;
use App\Http\Requests\Favorite\FavoriteStoreRequest;
use App\Models\Favorite;

class FavoriteController extends Controller {
    /**
     * @param Favorite $favorites
     * @return Response
     */
    public function index( Favorite $favorites ){
        $favorite   = $favorites
                        ->where('user_id', utils()->getUserId() )
                        ->paginate();

        if( $favorite->isNotEmpty() ){
            return $this->success( 'api/favorite.index.success', $favorite );
        }

        return $this->notfound( 'api/favorite.index.no_results' );
    }

    /**
     * @param FavoriteStoreRequest $request
     * @param Favorite $favorites
     * @return Response
     */
    public function store( FavoriteStoreRequest $request, Favorite $favorites ){
        $params     = [
            'user_id'       => utils()->getUserId(),
            'provider_id'   => $request->provider_id
        ];

        $favorite   = $favorites->updateOrCreate( $params, $params );
        if( $favorite ){
            return $this->success( 'api/favorite.create.success', $favorite->makeHidden('provider') );
        }

        return $this->error( 'api/favorite.create.failed' );
    }

    /**
     * @param FavoriteDeleteRequest $request
     * @param Favorite $favorites
     * @param $favorite_id
     * @return Response
     */
    public function destroy( FavoriteDeleteRequest $request, Favorite $favorites, $favorite_id ){
        if( $favorites->find( $favorite_id )->delete() ){
            return $this->success( 'api/favorite.delete.success' );
        }

        return $this->error( 'api/favorite.delete.failed' );
    }
}
