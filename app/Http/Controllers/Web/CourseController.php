<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class CourseController extends Controller {
    public function show( $course_slug, $course_id ){
        return redirect(env('APP_ONE_LINK'));
    }
}
