<?php

namespace App\Models;

class ProvidersFacility extends Model {
    public $timestamps      = false;
    protected $fillable     = [
        'provider_id',
        'facility_id'
    ];


    ### RELATIONS (BEGIN) ###
    public function provider(){
        return $this->belongsTo( 'App\Models\Provider', 'provider_id', 'id');
    }

    public function facilities(){
        return $this->belongsTo( 'App\Models\Facility', 'facility', 'id');
    }
    ### RELATIONS (END) ###
}
