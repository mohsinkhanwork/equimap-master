<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Spatie\Geocoder\Geocoder;
use GuzzleHttp\Client;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Storage;

class Utils{
    function isProvider(){
        return $this->guardName() == 'web' && auth()->user()->hasRole('vendor');
    }

    function guardName(){
        if( auth()->guard('api')->check() ){
            return 'api';
        }
        elseif( auth()->guard('web')->check() ){
            return 'web';
        }

        return null;
    }

    function getGeoCoder( $country='AE' ){
        $geocoder   = new Geocoder( new Client() );
        $geocoder->setApiKey( config('geocoder.key') );
        $geocoder->setCountry( config('geocoder.country', $country ) );

        return $geocoder;
    }

    function getAddressByCoords( $lat, $lng, $parse=false ){
        return $parse ?
                    utils()->parseAddressIntoSchema( $lat, $lng )
                        : $this->getGeoCoder()->getAddressForCoordinates( $lat, $lng );
    }

    function parseAddressIntoSchema( $lat, $lng ){
        $listAddresses      = $this->getGeoCoder()->getAllAddressesForCoordinates( $lat, $lng );
        $returnAddress      = [];
        $accuracyRequired   = false;

        if( !empty( $listAddresses ) ){
            $addressCollection  = collect( $listAddresses );
            if( in_array( 'ROOFTOP', $addressCollection->pluck('accuracy')->toArray() ) ){
                $accuracyRequired   = 'ROOFTOP';
            }

            foreach( $listAddresses as $listAddress ){
                if( $accuracyRequired && $listAddress['accuracy'] != $accuracyRequired ){
                    continue;
                }

                if( isset( $listAddress['formatted_address'] ) ){
                    $returnAddress['address'] = $listAddress['formatted_address'];
                }

                if( isset( $listAddress['address_components'] ) ){
                    foreach( $listAddress['address_components'] as $component ){
                        if( in_array( 'country', $component->types ) ){
                            $returnAddress['country'] = $component->short_name;
                        }
                        elseif( in_array( 'locality', $component->types ) || in_array( 'administrative_area_level_1', $component->types ) ){
                            $returnAddress['city'] = $component->short_name;
                        }
                    }
                }
            }
        }

        return $returnAddress;
    }

    function hashPassword( $password ){
        return Hash::make( $password );
    }

    function verifyPassword( $password, $dbPassword ){
        return Hash::check( $password, $dbPassword );
    }

    function isUserLogged(){
        return auth('sanctum')->check();
    }

    function user(){
        return auth('sanctum')->check() ? auth('sanctum')->user() : null;
    }

    function getUserId(){
        return auth('sanctum')->check() ? utils()->user()->id : null;
    }

    function getAuthToken(){
        return request()->bearerToken();
    }

    function tokenCan( $ability ){
        return auth('sanctum')->hasUser() ? auth('sanctum')->user()->tokenCan( $ability ) : false;
    }

    function cannot( $action, $model ){
        return auth('sanctum')->hasUser() ? utils()->user()->cannot( $action, $model ) : true;
    }

    function can( $action, $model ){
        return utils()->user()->can( $action, $model );
    }

    function slug( $string ){
        return Str::slug( $string, '-' );
    }

    function randomStr( $length = 10 ){
        return Str::random( $length );
    }

    function validateFcmToken( $token ){
        $service    = Firebase::messaging();
        try {
            $validate = $service->validateRegistrationTokens( $token );
            if( isset( $validate['valid'] ) && in_array( $token, $validate['valid'] ) ){
                return true;
            }
        } catch (MessagingException $e) {} catch (FirebaseException $e) {}

        return false;
    }

    function validateFbaToken( $token ){
        $phone      = utils()->getPhoneFromFbaToken( $token );
        return $phone ?? true;
    }

    function getPhoneFromFbaToken( $token=null ){
        if( !$token ){
            return false;
        }

        $service    = Firebase::auth();
        try {
            $validate = $service->verifyIdToken( $token );
            return $validate->claims()->get('phone_number');
        }
        catch( FailedToVerifyToken $e ){} catch (FirebaseException $e) {}

        return false;
    }

    function daysOfWeek(){
        return array_map( 'strtolower', Carbon::getDays() );
    }

    function dayFromDate( $date ){
        return Str::lower( \Carbon\Carbon::parse( $date )->format('l') );
    }

    function currentTime(){
        return Carbon::now();
    }

    function currentDate(){
        return Carbon::now()->format('Y-m-d');
    }

    function hoursInBetween( $start, $end ){
        $hoursInBetween = [];
        for( $i=$start; $i<=$end; $i++){
            $hoursInBetween[]   = $i;
        }

        return $hoursInBetween;
    }

    function getMorphableName( $className ){
        return Str::lower( Str::replace( "App\Models\\", "", $className ) );
    }

    function getSqlQuery( $builder ){
        $query  = str_replace(array('?'), array('\'%s\''), $builder->toSql());
        $query  = vsprintf($query, $builder->getBindings());

        return $query;
    }

    function getModelPath( $modeName ){
        return "\\App\Models\\" . Str::studly( $modeName );
    }

    function deleteFile( $filePath ){
        if( Storage::exists( $filePath ) ){
            Storage::delete( $filePath );
        }
    }

    function getPaymentAmount( $amount, $currency, $inverse=false ){
        $factor = in_array( $currency, [ 'BHD', 'KWD', 'OMR' ] ) ? 1000 : 100;

        return $amount > 0
                        ? $inverse === false
                            ? $amount*$factor
                            : $amount/$factor
                        : $amount;
    }

    function getTaxAmount( $amount ){
        if( $amount > 0 ){
            $vatRate        = 5;
            $vatDivisor     = 1 + ( $vatRate / 100 );
            $priceBeforeVat = $amount / $vatDivisor;
            $vatAmount      = $amount - $priceBeforeVat;

            return $this->formatAmount( $vatAmount, 2 );
        }
    }

    function getCommissionAmount( $amount, $rate=20 ){
        return $this->formatAmount( ( $amount * ( $rate / 100 ) ), 2 );
    }

    function formatAmount( $amount, $currency='AED' ){
        return number_format( $amount, 2, '.', '' );
    }

    function isCollection( $object ){
        $collectionParents  = [
            "Illuminate\Database\Eloquent\Collection"
        ];

        return is_object( $object ) && !empty(  array_intersect( class_parents( $object ), $collectionParents ) );
    }

    function isPagination( $object ){
        return is_object( $object ) && get_class( $object ) == "Illuminate\Pagination\LengthAwarePaginator";
    }

    function response(){
        return new Response();
    }

    function str(){
        return new Str;
    }

    function singular( $value ){
        return Str::singular( $value );
    }

    function plural( $value ){
        return Str::plural( $value );
    }

    function capitalize( $value ){
        return Str::ucfirst( $value );
    }
}
