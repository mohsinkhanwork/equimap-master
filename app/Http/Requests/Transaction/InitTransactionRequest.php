<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\BaseRequest;
use App\Models\Booking;
use App\Rules\PaymentTokenRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InitTransactionRequest extends BaseRequest{
    public function rules(){
        $bookableType           = $this->has( 'bookable_type' ) ? $this->bookable_type : 'service';
        $requiredWithService    = Rule::requiredIf($bookableType == 'service' );
        $bookableTable          = Str::plural( $bookableType );

        return [
            'bookable_type' => [ 'required', Rule::in( array_keys( Booking::getBookingTypes() ) ) ],
            'expiry'        => [ 'required', 'date', 'before:5 minutes', 'date_format:Y-m-d H:i:s' ],
            'bookable_id'   => [ 'required', "exists:{$bookableTable},id,active,1,approved,1" ],
            'date'          => [ $requiredWithService ],
            'schedule_id'   => [ $requiredWithService, 'exists:schedules,id' ],
            'services_id'   => [ $requiredWithService, 'exists:services,id,active,1,approved,1' ],
            'horse_id'      => [ 'sometimes', 'exists:horses,id', Rule::exists('services_horses', 'horse_id' )->where( 'service_id', $this->service_id ) ],
            'trainer_id'    => [ 'sometimes', 'exists:trainers,id', Rule::exists('services_trainers', 'trainer_id' )->where( 'service_id', $this->service_id ) ],
            'pay_token'     => [ 'required', new PaymentTokenRule( $this ) ]
        ];
    }

    protected function failedValidation( Validator $validator ){
        return utils()
            ->response()
            ->status( 'error' )
            ->items( $validator->errors() )
            ->template( $this->getResponseViewTemplate() )
            ->view( $this->getResponseMessage(), $this->getResponseReplacers() );
    }

    protected function getResponseViewTemplate(){
        return 'pages.system.payment.stripe';
    }

    protected function getResponseMessage(){
        return 'web/transaction.failed';
    }
}
