<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

use App\Models\Service;

class CalendarCollection extends Collection{
    public function IsBlocked(){
        foreach( $this as $item ){
            if( $item->event_type == $item::TYPE_BLOCK ){
                return true;
            }
        }

        return false;
    }
}
