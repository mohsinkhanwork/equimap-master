<?php


namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsersProfile\UsersProfileUpdateRequest;
use App\Models\UsersProfile;

class UserProfileController extends Controller {
    /**
     * @param UsersProfile $usersProfile
     * @return Response
     */
    public function show( UsersProfile $usersProfile ){
        $usersProfiles   = $usersProfile
                            ->where( 'user_id', utils()->getUserId() )
                            ->get();

        if( $usersProfiles->isNotEmpty() ){
            return $this->success( 'api/users_profile.read.success', $usersProfiles->first() );
        }

        return $this->notfound( 'api/api/users.read.not_found' );
    }

    /**
     * @param UsersProfileUpdateRequest $request
     * @param UsersProfile $usersProfile
     * @return Response
     */
    public function update( UsersProfileUpdateRequest $request, UsersProfile $usersProfiles ){
        $usersProfile   = $usersProfiles
                            ->updateOrCreate(
                                [ 'user_id' => utils()->getUserId() ],
                                $request->validated()
                            );

        if( $usersProfile ){
            if( $request->has('image') ){
                $usersProfile->uploadImage( $request->file('image') );
            }

            return $this->success( 'api/users_profile.update.success' );
        }

        return $this->error( 'api/users_profile.update.failed' );
    }
}
