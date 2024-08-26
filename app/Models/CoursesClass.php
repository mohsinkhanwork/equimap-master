<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CoursesClass extends Model{
    use SoftDeletes;

    protected $fillable     = [
        'course_id',
        'sort',
        'name',
        'description'
    ];

    protected $dates        = [
        'deleted_at'
    ];

    public function course(){
        return $this->belongsTo( 'App\Models\Course', 'course_id', 'id' );
    }
}
