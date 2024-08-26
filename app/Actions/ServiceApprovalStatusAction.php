<?php

namespace App\Actions;

use App\Models\Service;

class ServiceApprovalStatusAction{
    public static function denied( $service ){
        if( utils()->isProvider() ){
            $difference = array_diff( $service->getOriginal(), $service->getAttributes() );
            if( !empty( $difference ) ){
                $updatedKeys        = array_keys( $difference );
                $restrictedFields   = Service::getServiceRestrictedFields();
                if( !empty( array_intersect( $updatedKeys, $restrictedFields ) ) ){
                    return true;
                }
            }
        }
    }
}
