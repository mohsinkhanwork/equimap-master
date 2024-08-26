<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\BaseRequest;
use App\Models\Course;
use Illuminate\Validation\Rule;

class CourseClassUpdateRequest extends BaseRequest{
    public function prepareForValidation(){
        if( auth()->guard('web')->user()->cannot('approve course') ){
            $this->request->remove('approved');
            $this->request->remove('notes');
        }
        else{
            if( $this->has('approved') && $this->approved == 1 ){
                $this->merge( [ 'notes' => null ] );
            }
            else{
                $this->merge( [ 'approved' => 0 ] );
            }
        }

        // get class id that we are updating and merge with request
        $class_id   = $this->route('class_id');
        if( $class_id > 0 ){
            $this->mergeIfMissing( [ 'class_id' => $class_id ] );
        }
    }

    public function rules(){
        return [
            'class_id'              => [ 'required', 'exists:courses_classes,id'],
            'name'                  => [ 'required', 'string', 'min:5' ],
            'description'           => [ 'required', 'min:25' ],
            'sort'                  => [ 'sometimes', 'numeric', 'min:0' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/course.class.update.failed';
    }
}
