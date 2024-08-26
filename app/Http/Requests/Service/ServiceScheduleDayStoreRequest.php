<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\BaseRequest;
use App\Models\Service;
use Illuminate\Validation\Rule;

/**
 * @property mixed $schedule
 */

class ServiceScheduleDayStoreRequest extends BaseRequest{
    public function prepareForValidation(){
        $schedule   = [];
        if( $this->has('schedule') ){
            foreach( $this->schedule as $day => $data ){
                // remove all null values
                $data               = array_filter( $data, 'strlen' );
                $data['day']        = $day;

                // if active is not set than add as zero
                if( !isset( $data['active'] ) ){
                    $data['active'] = 0;
                }

                // if price is not set than put back to zero
                if( !isset( $data['price'] ) || $data['price'] <= 0 ){
                    $data['price']  = 0;
                }

                $schedule[$day] = $data;
            }
        }

        $this->merge( [ 'schedule' => $schedule ] );
    }

    public function rules(){
        return [
            'schedule.*'            => [ 'required', 'array' ],
            'schedule.*.id'         => [ 'sometimes', 'exists:schedules,id' ],
            'schedule.*.active'     => [ 'sometimes', 'in:1,0' ],
            'schedule.*.day'        => [ 'sometimes', Rule::In( array_keys( Service::getServiceDays() ) ) ],
            'schedule.*.start_time' => [ 'required_if:schedule.*.active,1', 'date_format:H:i' ],
            'schedule.*.end_time'   => [ 'required_if:schedule.*.active,1', 'date_format:H:i', 'after:schedule.*.start_time' ],
            'schedule.*.price'      => [ 'sometimes', 'numeric', 'between:0,10000']
        ];
    }

    protected function getResponseMessage(){
        return 'api/service.schedule.create.failed';
    }
}
