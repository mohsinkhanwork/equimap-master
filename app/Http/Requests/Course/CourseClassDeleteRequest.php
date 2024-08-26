<?php

namespace App\Http\Requests\Course;

use App\Actions\CastAttributes;
use App\Http\Requests\BaseRequest;

/**
 * @property mixed login
 * @property mixed password
 */

class CourseClassDeleteRequest extends BaseRequest{
    public function prepareForValidation(){
        // get class id that we are updating and merge with request
        $class_id   = $this->route('class_id');
        if( $class_id > 0 ){
            $this->mergeIfMissing( [ 'class_id' => $class_id ] );
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(){
        return [
            'class_id'              => [ 'required', 'exists:courses_classes,id'],
        ];
    }

    protected function getResponseMessage(){
        return 'api/course.class.delete.failed';
    }
}
