<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller {
    function store( Request $request, $scheduleable_type, $scheduleable_id ){
        $scheduleable   = utils()->getModelPath( $scheduleable_type );
        if( !class_exists( $scheduleable ) ){
            return $this->error( 'api/schedules.create.invalid_type' );
        }

        $scheduleable   = new $scheduleable();
        $scheduleables  = $scheduleable->where( 'id', $scheduleable_id )->get();
        if( $scheduleables->isEmpty() ){
            return $this->error( 'api/schedules.create.invalid_unauthorized', null, [ 'type' => $scheduleable_type ] );
        }

        $scheduleables  = $scheduleables->first();
        $timeRequired   = $scheduleable_type == 'service' && $scheduleables->unit == 'hour';
        $validate       = Validator::make( $request->all(), [
            'day'           => [ 'required', Rule::in( utils()->daysOfWeek() ) ],
            'start_time'    => [ 'numeric', 'between:1,23', Rule::requiredIf( $timeRequired ) ],
            'end_time'      => [ 'numeric', 'between:2,24', 'gt:start_time', Rule::requiredIf( $timeRequired ) ],
            'price'         => [ 'sometimes', 'numeric', 'gte:0' ]
        ]);

        if( $validate->fails() ){
            return $this->error( 'api/schedules.create.failed', $validate->errors() );
        }

        if( $scheduleables->scheduleExists( $request ) ){
            return $this->error( 'api/schedules.create.already_exists', null, [ 'type' => $scheduleable_type ] );
        }

        if( utils()->can('update', $scheduleables ) ){
            return $scheduleables
                ->createMorphAndReturn( 'schedules', $request->all() );
        }

        return $this->error( 'api/general.unauthorized' );
    }

}
