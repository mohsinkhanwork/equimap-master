<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicesHorse extends Model {
    use HasFactory;

    public $timestamps      = false;
    protected $fillable     = [
        'service_id',
        'horse_id'
    ];


    ### RELATIONS (BEGIN) ###
    public function service(){
        return $this->belongsTo( 'App\Models\Service', 'service_id', 'id');
    }

    public function horse(){
        return $this->belongsTo( 'App\Models\Horse', 'horse_id', 'id');
    }
    ### RELATIONS (END) ###
}
