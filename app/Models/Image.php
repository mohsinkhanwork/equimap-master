<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Image extends Model {
    use HasFactory;

    const IMAGE_TYPES       = [
        'cover',
        'gallery',
        'icon',
        'profile',
        'banner'
    ];

    public $timestamps      = true;
    protected $fillable     = [
        'name',
        'ext',
        'path',
        'hash',
        'type',
        'user_id',
        'imageable_type',
        'imageable_id'
    ];

    protected $hidden       = [
        'imageable_type',
        'imageable_id'
    ];

    protected $attributes   = [
        'type' => 'gallery'
    ];

    protected $appends      = [
        'url'
    ];

    protected static function booted(){
        parent::boot();
        static::deleted( function( $image ){
            // delete image from storage if there is no other imageable available
            $allImages  = Image::where( 'hash', $image->hash )->get();
            if( $allImages->isEmpty() ){
                utils()->deleteFile( $image->path );
            }
        });
    }

    public static function getImageTypes(){
        return self::IMAGE_TYPES;
    }

    public function getUrlAttribute(){
        return asset( 'storage/' . $this->path );
    }

    public function upload( $file, $model, $type ){
        $images     = $this;
        if( $file->isValid() ){
            // If file already exists, link it to another imageable as well.
            $keyName        = $model->primaryKey;
            $fileHash       = md5_file( $file->path() );
            $existing       = $images->hashExists( $fileHash );
            $path           = $existing === false
                ? $file->storePublicly( $type )
                : $existing->path;

            $stored         = $this->create([
                'name'      => utils()->slug( $file->getClientOriginalName() ),
                'path'      => $path,
                'ext'       => $file->extension(),
                'user_id'   => auth()->id(),
                'hash'      => $fileHash,
                'type'      => $type,
                'imageable_type'    => $model->getMorphClass(),
                'imageable_id'      => $model->$keyName
            ]);

            if( $stored->id > 0 ){
                return $stored;
            }
        }
    }

    public function hashExists( $hash ){
        $image  = $this->where( 'hash', $hash )->get();
        return $image->isNotEmpty() ? $image->first() : false;
    }

    public function imageable(){
        return $this->morphTo();
    }

    public function user(){
        return $this->hasOne( 'App\Models\User', 'id', 'user_id');
    }
}
