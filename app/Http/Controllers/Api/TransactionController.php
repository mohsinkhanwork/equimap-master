<?php


namespace App\Http\Controllers\Api;

use App\Actions\GeneratePaymentCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\InitTransactionRequest;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BookingPackageAvailabilityService;
use App\Services\BookingServiceAvailabilityService;
use App\Services\BookingTripAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller {
    function init( InitTransactionRequest $request ){
        $bookableType       = $request->bookable_type;

        if( $bookableType == 'trip' ){
            $availabilityService    = new BookingTripAvailabilityService();
            $availability           = $availabilityService->setup( $request );
        }
        elseif( $bookableType == 'package' ){
            $availabilityService    = new BookingPackageAvailabilityService();
            $availability           = $availabilityService->setup( $request );
        }
        elseif( $bookableType == 'service' ){
            $availabilityService    = new BookingServiceAvailabilityService();
            $availability           = $availabilityService->setup(
                    $request->date,
                    $request->bookable_id,
                    $request->schedule_id,
                    $request->only( [ 'horse_id', 'trainer_id', 'booking_id', 'notes', 'coupon' ] )
                );
        }

        if( !$availability->canSchedule() ){
            return $this->viewError( 'pages.system.payment.stripe', 'web/transaction.service_unavailable.' );
        }

        $service        = $availability->getBookable();
        $serviceCharges = $availability->getServiceCharges();

        if( $serviceCharges['price']['total'] > 0 ){
            $returnParams   = [ 'session_id' => '{CHECKOUT_SESSION_ID}', '_token' => utils()->getAuthToken() ];
            $metadata       = array_merge( $serviceCharges['schedule'], [ 'user_id'   => utils()->getUserId(), 'notes' => $availability->getNotes() ] );
            $paymentAmount  = utils()->getPaymentAmount( $serviceCharges['price']['balance'], $serviceCharges['price']['currency'] );

            // validate user has stripe account
            if( !utils()->user()->hasStripeId() ){
                utils()->user()->createAsStripeCustomer();
            }

            return utils()->user()->checkoutCharge( $paymentAmount, $service->name, 1, [
                'success_url'   => urldecode( route('pay.success', $returnParams ) ),
                'cancel_url'    => urldecode( route('pay.cancelled', $returnParams ) ),
                'currency'      => $serviceCharges['price']['currency'],
                'metadata'      => $metadata,
                'line_items'    => [
                    [
                        'quantity' => 1,
                        'price_data' => [
                            'unit_amount' => $paymentAmount,
                            'currency' => $serviceCharges['price']['currency'],
                            'product_data' => [
                                'name'      => $service->name
                            ]
                        ]
                    ]
                ]
            ]);
        }

        return $this->viewError( 'pages.system.payment.stripe', 'web/transaction.failed' );
    }

    function getStripeData( $sessionId ){
        return stripe( 'checkout.sessions' )->retrieve( $sessionId );
    }

    function createTransactionData( $gatewayData ){
        $paidAmount     = utils()->getPaymentAmount( $gatewayData['amount_total'], 'AED', true );
        $bookingId      = isset( $gatewayData['metadata']['booking_id'] ) && $gatewayData['metadata']['booking_id'] > 0 ? $gatewayData['metadata']['booking_id'] : false;
        return [
            'code'      => $gatewayData['payment_intent'],
            'status'    => $gatewayData['payment_status'],
            'booking_id'=> $bookingId,
            'user_id'   => $gatewayData['metadata']['user_id'],
            'processor' => 'stripe',
            'amount'    => utils()->formatAmount( $paidAmount ),
            'tax'       => utils()->getTaxAmount( $paidAmount ),
            'commission'=> utils()->getCommissionAmount( $paidAmount, 20 ),
            'settled'   => false,
            'currency'  => utils()->str()->upper( $gatewayData['currency'] ),
            'notes'     => $gatewayData['metadata'],
        ];
    }

    function paid( Request $request, User $user, Booking $bookings ){
        if( $request->has('session_id') ){
            $user   = auth()->check() ? auth()->user() : null;
            if( $user ){
                $stripePayment      = $this->getStripeData( $request->session_id );
                if( $stripePayment['payment_status'] == 'paid' && $stripePayment['status'] == 'complete' ){
                    // get transaction data
                    $transactionData= $this->createTransactionData( $stripePayment );
                    $bookingId      = $transactionData['booking_id'];

                    // set booking data
                    $metadata       = $transactionData['notes'];
                    $notes          = isset( $metadata['notes'] ) && $metadata['notes'] ? base64_decode( $metadata['notes'] ) : null;
                    $startDateTime  = Carbon::parse( "{$metadata['date']} {$metadata['check_in']}");
                    $endDateTime    = Carbon::parse( "{$metadata['date']} {$metadata['check_out']}");

                    // if we have an existing booking than fetch it, else create one.
                    if( $bookingId > 0 ){
                        $booking    = $bookings->find( $bookingId );
                        $booking->update([
                            'bookable_id'   => $metadata['bookable_id'],
                            'horse_id'      => $metadata['horse_id'] ? $metadata['horse_id'] : null,
                            'trainer_id'    => $metadata['trainer_id'] ? $metadata['trainer_id'] : null,
                            'start_time'    => $startDateTime,
                            'end_time'      => $endDateTime,
                            'notes'         => $notes
                        ]);
                    }

                    // if transaction data exists we can exist now
                    $transaction    = Transaction::where( 'code', $transactionData['code'] )->get();
                    if( $transaction->isNotEmpty() ){
                        $bookings   = $transaction->first()->booking()->get();
                        return $this->viewSuccess( 'pages.system.payment.stripe', 'web/transaction.success', $bookings );
                    }

                    // if we dont have an existing booking than create one.
                    if( !isset( $booking ) || $booking == null ){
                        $bookableType   = "App\Models\\" . Str::studly( $metadata['bookable_type'] );
                        $booking        = $bookings->create([
                            'user_id'       => isset( $metadata['user_id'] ) ? $metadata['user_id'] : utils()->getUserId(),
                            'bookable_id'   => $metadata['bookable_id'],
                            'bookable_type' => $bookableType,
                            'horse_id'      => $metadata['horse_id'] ? $metadata['horse_id'] : null,
                            'trainer_id'    => $metadata['trainer_id'] ? $metadata['trainer_id'] : null,
                            'start_time'    => $startDateTime,
                            'end_time'      => $endDateTime,
                            'status'        => 'scheduled',
                            'reference'     => GeneratePaymentCode::handle(),
                            'notes'         => $notes
                        ]);
                    }

                    if( empty( $booking ) ){
                        return $this->viewError( 'pages.system.payment.stripe', 'web/transaction.invalid_booking' );
                    }

                    $transaction    = $booking->addTransaction( $transactionData );
                    if( $transaction->id > 0 ){
                        return $this->viewSuccess( 'pages.system.payment.stripe', 'web/transaction.success', $booking );
                    }
                }
            }
        }

        return $this->viewError( 'pages.system.payment.stripe', 'web/transaction.failed' );
    }

    function cancelled( Request $request ){
        if( $request->has('session_id') ){
            $user   = utils()->user();
            if( $user ){
                $transaction    = stripe( 'checkout.sessions' )->retrieve( $request->session_id );
                return $this->viewError( 'pages.system.payment.stripe', 'web/transaction.failed', $transaction );
            }
        }
    }
}
