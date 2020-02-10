<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataRestored;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

// Custom includes
// ==========================================================>
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

use Carbon\Carbon;
use App\Affectation;


class ReadChartController extends VoyagerBaseController
{
    use BreadRelationshipParser;

    // Read view Chart data
    public function readData(Request $request){
        // getting data depending on the slug and id
        if($request['read_slug'] == 'Companies'){
            $affect_chart = $this->readField_Chart('company_id', $request['id']);
        }
        else if($request['read_slug'] == 'Customers'){
            $affect_chart = $this->readField_Chart('client_id', $request['id']);
        }
        else if($request['read_slug'] == 'Projects'){
            $affect_chart = $this->readField_Chart('project_id', $request['id']);
        }
        else if($request['read_slug'] == 'Providers'){
            $affect_chart = $this->readField_Chart('provider_id', $request['id']);
        }
        else if($request['read_slug'] == 'Sites'){
            $affect_chart = $this->readField_Chart('site_id', $request['id']);
        }
        else if($request['read_slug'] == 'Prestations'){
            $affect_chart = $this->readField_Chart('prestation_id', $request['id']);
        }

        // for 12 month
        $affect_month = array_fill(1, 12, 0);
        $months = array_fill(1, 12, 0);

        $now = Carbon::now();

        // redefining months based on the actual month beeing the last index value
        for($i = 1; $i <= 12; $i++){
            $months[$i] = $now->addMonthWithNoOverflow()->locale('fr')->isoFormat('MMM');
        }

        // filling assignments & supplies data arrays
        for($i = 1; $i <= 12; $i++){
            foreach($affect_chart as $affect){
                if( $months[$i] == (Carbon::parse($affect->date_affectation)->locale('fr')->isoFormat('MMM')) )
                {
                    $affect_month[$i] += $affect->montant;
                }
            }
        }

        // getting logs refactored
        $affect_logs = $this->affect_logs($affect_chart);

        return Response()->json(array('affect_logs' => $affect_logs, 'affect_month' => $affect_month, 'months' => $months) );
    }

    // getting data OF THE LAST YEAR depending on the field and id
    public function readField_Chart($field, $id) {
        // defining start and end date (for the months rotation -> to get prev months of last year)
        $start_date = Carbon::now()->subYearNoOverflow()->addMonthNoOverflow()->day(1)->toDateString();
        $end_date = Carbon::now()->toDateString();

        // DB request
        $affect_chart = Affectation::whereBetween('date_affectation',[$start_date, $end_date])
                                    ->where($field, $id)
                                    ->orderBy('date_affectation', 'DESC')
                                    ->get();
        return $affect_chart;
    }

    // affectation logs refactored
    public function affect_logs($current_affect){
        if($current_affect != null){
            foreach($current_affect as $af){
                $af->montant = number_format($af->montant,2,"."," ");
                $af->company_id = isset( $af->company->nom ) ? $af->company->nom : '-' ;
                $af->client_id = isset( $af->customers->nom ) ? $af->customers->nom : '-' ;
                $af->project_id = isset( $af->projects->titre ) ? $af->projects->titre : '-' ;
                $af->provider_id = isset( $af->provider->nom ) ? $af->provider->nom : '-' ;
                $af->site_id = isset( $af->sites->nom ) ? $af->sites->nom : '-' ;
                $af->prestation_id = isset( $af->prestations->titre ) ? $af->prestations->titre : '-' ;
                $af->etat = ( $af->etat ) ? "Décaissé" : "Non décaissé" ;
            }
        }

        return $current_affect;
    }
}
