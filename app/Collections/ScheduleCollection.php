<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ScheduleCollection extends Collection{
    /**
     * @param $params array
     * @return ScheduleCollection
     */
    public function filterByParams( $params ){
        // keep relevant data only
        $params = [
            'day'       => isset( $params['day'] ) ? $params['day'] : null,
            'start_time'=> isset( $params['start_time'] ) ? $params['start_time'] : null,
            'end_time'  => isset( $params['end_time'] ) ? $params['end_time'] : null,
        ];

        return $this->filter( function( $item ) use ( $params ){
            if( strtolower( $params['day'] ) === strtolower( $item->day )
                && $params['start_time'] === $item->start_time
                && $params['end_time'] === $item->end_time ){
                return true;
            }
        });
    }

    public function groupedByDaySingle(){
        return $this->groupedByDay( true );
    }

    public function groupedByDay( $singleLevel=false ){
        $groupedByDays  = [];
        foreach( $this->items as $item ){
            $dayName                    = utils()->slug( $item->day );
            if( $singleLevel ){
                $groupedByDays[$dayName]= $item->makeHidden(['day', 'price']);
            }
            else{
                $groupedByDays[$dayName][]  = $item->makeHidden(['day', 'price']);
            }
        }

        return ( new ScheduleCollection( $groupedByDays ) );
    }
}
