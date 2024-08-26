<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\BaseRequest;
use App\Models\Course;
use Illuminate\Validation\Rule;

class CourseClassStoreRequest extends BaseRequest{
    public function prepareForValidation(){
        // get course id that we are updating and merge with request
        $course_id  = $this->route('course_id');
        if( $course_id > 0 ){
            $this->mergeIfMissing( [ 'course_id' => $course_id ] );
        }
    }

    public function rules(){
        return [
            'course_id'             => [ 'required', 'exists:courses,id'],
            'name'                  => [ 'required', 'string', 'min:5' ],
            'description'           => [ 'required', 'min:25' ],
            'sort'                  => [ 'sometimes', 'numeric', 'min:0' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/course.class.create.failed';
    }
}
