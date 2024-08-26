<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;

class UsersProfile extends Model {
    use HasFactory, Authorizable;

    public $timestamps      = true;
    protected $fillable     = [
        'name',
        'email',
        'gender',
        'birthday',
        'language',
        'level',
        'weight',
        'user_id'
    ];

    protected $casts        = [
        'birthday'  => 'date:Y-m-d'
    ];

    protected $appends      = [ 'profile_image' ];

    public function getProfileImageAttribute(){
        return $this->image()->first();
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'id', 'user_id');
    }

    public function uploadImage( $file ){
        // fetch old image and upload new
        $oldImage    = $this->image()->get();
        $newImage    = ( new Image )->upload( $file, $this, 'profile' );

        // delete old image
        if( $newImage->id > 0 && $oldImage->isNotEmpty() ){
            $oldImage->first()->delete();
        }
    }

    public function image(){
        return $this->morphOne('App\Models\Image', 'imageable')->where('type', 'profile');
    }
}
