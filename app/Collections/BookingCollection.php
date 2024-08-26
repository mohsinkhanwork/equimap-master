<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BookingCollection extends Collection{
    public function isType( $key_name, $type_id ){
        return $this->filter( function($item ) use ( $key_name, $type_id ) {
            return !is_null( $item->$key_name ) && $item->$key_name == $type_id;
        });
    }

    public function isTrip( $trip_id ){
        return $this->filter->isTrip()->isType( 'bookable_id', $trip_id );
    }

    public function isService( $service_id ){
        return $this->filter->isService()->isType( 'bookable_id', $service_id );
    }

    public function isTrainer( $trainer_id ){
        return $this->isType( 'trainer_id', $trainer_id );
    }

    public function isHorse( $horse_id ){
        return $this->isType( 'horse_id', $horse_id );
    }
}
