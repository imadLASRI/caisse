<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alimentation extends Model
{
    //
    
    function provenance(){
        return $this->belongsTo('App\Provenance','provenance_id');
    }

    function methode(){
        return $this->belongsTo('App\Method','method_id');
    }
}
