<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class TripsItineraryCollection extends Collection{
    public function groupByDate(){
        $grouped    = [];
        foreach( $this->items as $item ){
            $date   = $item->date->format('Y-m-d');
            $grouped[$date]= $item;
        }

        return $grouped;
    }
}
