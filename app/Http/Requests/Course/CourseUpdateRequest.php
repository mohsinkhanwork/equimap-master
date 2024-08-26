<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\BaseRequest;
use App\Models\Course;
use Illuminate\Validation\Rule;

class CourseUpdateRequest extends BaseRequest{
    public function rules(){
        // get trip id that we are updating and merge with request
        $course_id  = $this->route('course_id');
        if( $course_id > 0 ){
            $this->mergeIfMissing( [ 'course_id' => $course_id ] );
        }

        // approval and notes
        if( !auth()->check() || auth()->guard('web')->user()->cannot('approve course') ){
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

        // active check
        $this->mergeIfMissing( ['active' => $this->request->has('active') ? 1 : 0 ] );

        // make sure name is unique within provider
        $uniqueRule         = Rule::unique('courses')
                                    ->ignore( $course_id )
                                    ->where('provider_id', $this->request->get('provider_id'));

        return [
            'active'        => [ 'sometimes', 'in:0,1' ],
            'approved'      => [ 'in:0,1' ],
            'sort'          => [ 'sometimes', 'numeric', 'gte:0'],
            'name'          => [ 'required', $uniqueRule ],
            'description'   => [ 'min: 25' ],
            'progression_type'  => [ 'sometimes', Rule::In( array_keys( Course::getProgressionTypes() ) ) ],
            'price'         => [ 'sometimes', 'numeric', 'min:1,max:10000' ],
            'provider_id'   => [ 'sometimes', 'exists:providers,id' ],
            'category_id'   => [ 'sometimes', 'exists:categories,id' ],
            'notes'         => [ 'sometimes' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/trip.update.failed';
    }
}
