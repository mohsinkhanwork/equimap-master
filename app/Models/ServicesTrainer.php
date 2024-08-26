<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicesTrainer extends Model {
    use HasFactory;

    public $timestamps      = false;
    protected $fillable     = [
        'service_id',
        'trainer_id'
    ];


    ### RELATIONS (BEGIN) ###
    public function service(){
        return $this->belongsTo( 'App\Models\Service', 'service_id', 'id');
    }

    public function trainer(){
        return $this->belongsTo( 'App\Models\Trainer', 'trainer_id', 'id');
    }
    ### RELATIONS (END) ###
}
