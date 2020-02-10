<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    Protected $guarded = array();

    function provider(){
        return $this->belongsTo('App\Provider');
    }

    function customers(){
        return $this->belongsTo('App\Customer','client_id');
    }

    function projects(){
        return $this->belongsTo('App\Project','project_id');
    }


    function prestations(){
        return $this->belongsTo('App\Prestation','prestation_id');
    }

    function company(){
        return $this->belongsTo('App\Company','company_id');
    }

    function sites(){
        return $this->belongsTo('App\Site','site_id');
    }
   
}
