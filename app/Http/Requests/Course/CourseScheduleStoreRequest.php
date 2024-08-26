<?php

namespace App\Http\Requests\Course;

use App\Http\Requests\BaseRequest;
use App\Models\Course;
use Illuminate\Validation\Rule;

class CourseScheduleStoreRequest extends BaseRequest{
    public function prepareForValidation(){
        $scheduled  = $this->has('scheduled') ? $this->normalize( $this->scheduled ) : [];
        $schedule   = $this->has('schedule')  ? $this->normalize( $this->schedule ) : [];

        $this->merge( [ 'schedule' => $schedule, 'scheduled' => $scheduled ] );
    }

    public function rules(){
        return [
            'scheduled.*'                      => [ 'required', 'array' ],
            'scheduled.*.slots.*.active'       => [ 'sometimes', 'in:0,1' ],
            'scheduled.*.slots.*.id'           => [ 'sometimes', 'exists:schedules,id' ],
            'scheduled.*.slots.*.day'          => [ 'sometimes', Rule::In( array_keys( Course::getCourseDays() ) ) ],
            'scheduled.*.slots.*.start_time'   => [ 'sometimes', 'date_format:H:i' ],
            'scheduled.*.slots.*.end_time'     => [ 'sometimes', 'date_format:H:i', 'after:scheduled.*.slots.*.start_time' ],
            'scheduled.*.slots.*.price'        => [ 'sometimes', 'numeric', 'between:0,10000'],

            'schedule.*'                      => [ 'required', 'array' ],
            'schedule.*.slots.*.active'       => [ 'sometimes', 'in:0,1' ],
            'schedule.*.slots.*.id'           => [ 'sometimes', 'exists:schedules,id' ],
            'schedule.*.slots.*.day'          => [ 'sometimes', Rule::In( array_keys( Course::getCourseDays() ) ) ],
            'schedule.*.slots.*.start_time'   => [ 'sometimes', 'date_format:H:i' ],
            'schedule.*.slots.*.end_time'     => [ 'sometimes', 'date_format:H:i',  'after:schedule.*.slots.*.start_time' ],
            'schedule.*.slots.*.price'        => [ 'sometimes', 'numeric', 'between:0,10000']
        ];
    }

    public function passedValidation(){
        $validated  = array_merge_recursive( $this->schedule, $this->scheduled );

        // remove older data
        $this->request->remove('active');
        $this->request->remove('schedule');
        $this->request->remove('scheduled');

        // add new data
        $this->merge( [ 'schedule' => $validated ] );
    }

    public function normalize( $input ){
        if( !empty( $input ) ){
            $schedule   = [];
            foreach( $input as $day => $data ){
                // remove all null values
                $slots      = array_filter( array_map( function( $value ){
                    if( isset( $value['active'] ) && $value['active'] == 0 ){
                        $value  = [
                            'active'    => 0,
                            'id'        => isset( $value['id'] ) ? $value['id'] : null
                        ];
                    }

                    return array_filter( $value, 'strlen' );
                }, $data ) );

                $data   = [
                    'slots'     => $slots
                ];

                $schedule[$day] = $data;
            }

            return $schedule;
        }
    }

    protected function getResponseMessage(){
        return 'api/course.schedule.create.failed';
    }
}
