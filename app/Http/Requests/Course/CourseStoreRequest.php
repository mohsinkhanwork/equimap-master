<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\BaseRequest;
use App\Models\Course;
use Illuminate\Validation\Rule;

class CourseStoreRequest extends BaseRequest{
    public function prepareForValidation(){
        $this->mergeIfMissing( [ 'active' => 0, 'notes' => config('general.course.default_notes') ] );
    }

    public function rules(){
        return [
            'active'        => [ 'required', 'in:0,1' ],
            'sort'          => [ 'sometimes', 'numeric', 'gte:0'],
            'name'          => [ 'required' ],
            'description'   => [ 'min: 25' ],
            'price'         => [ 'required', 'numeric', 'min:1,max:10000' ],
            'progression_type'  => [ 'required', Rule::In( array_keys( Course::getProgressionTypes() ) ) ],
            'provider_id'   => [ 'required', 'exists:providers,id' ],
            'category_id'   => [ 'required', 'exists:categories,id' ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/course.create.failed';
    }
}
