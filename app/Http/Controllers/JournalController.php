<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerController;
use TCG\Voyager\Facades\Voyager;

use Carbon\Carbon;
use App\Company;
use App\Affectation;
use App\Alimentation;
use App\Customer;
use App\Project;
use App\Provenance;
use App\Method;
use App\Site;
use App\Prestation;
use App\Provider;
use App\Supervisor;

class JournalController extends VoyagerController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    public function index(){

        $totalalim = 0;       
        $totalaffect = 0;
        $totalaffectDecaisse = 0;
        $totalaffectNonDecaisse = 0;

        $totalAlimentation = $totalAffectation = 0;

        $etat = ["Non décaissé","Décaissé"];

        // Latest data
        // $latestAssignment = Affectation::all()->sortByDesc('date_affectation')->take(3);
        // $latestSupply = Alimentation::all()->sortByDesc('date_alimentation')->take(3);     

        $affectationsDecaisse = Affectation::where('etat', '1')->get();
        $affectationsNonDecaisse = Affectation::where('etat', '0')->get();
        $affectations = Affectation::all();
        $alimentations = Alimentation::all();        
        $bu = Company::all();

        // Affectation / BU (latest 5)

        // $affectations_dist_bu = Affectation::where('etat', '1')->distinct('company_id')->orderBy('company_id')->get('company_id');
        // all affectations
        $affectations_dist_bu = Affectation::distinct('company_id')->orderBy('company_id')->get('company_id');

        // $affectations_dec = Affectation::where('etat', '1')->get();
        // all affectations
        $affectations_dec = Affectation::all();

        $bu_keys = array();
        // filling keys with company names (strings) to use as keys after
        foreach($affectations_dist_bu as $affectations_dist){
            if(isset($affectations_dist->company->nom)){
                array_push($bu_keys, strval($affectations_dist->company->nom));
            }
        }

        // creating associative array 
        $affectation_by_bu = array_fill_keys($bu_keys, 0);

        // filling the associative array key(company name)=>value
        foreach($bu_keys as $name){
            foreach($affectations_dec as $affect){
                if(isset($affect->company->nom)){
                    if($affect->company->nom == $name){
                        $affectation_by_bu[$name] += $affect->montant;
                    }
                }
            }
        }
        
        // Total Caisse this year
        $alimYears = array_fill(0, Alimentation::all()->count(), 0);
        $affectYears = array_fill(0, Affectation::all()->count(), 0);
        $i=0;
        $j=0;
        //

         // affectation non decaissé
         foreach($affectationsNonDecaisse as $affectnondec){
            $totalaffectNonDecaisse += $affectnondec->montant;
        }

        foreach($alimentations as $alim){

            $yearTmp = strval(Carbon::parse($alim->date_alimentation)->year);
            if($yearTmp != strval(Carbon::now()->year)){
                $alimYears[$i++] = $yearTmp;
            }
            if( $yearTmp == strval(Carbon::now()->year)){
                $totalalim += $alim->montant;
            }

            $totalAlimentation +=$alim->montant;
        }
        foreach($affectations as $affect){
            $yearTmp = strval(Carbon::parse($affect->date_affectation)->year);

            if($yearTmp != strval(Carbon::now()->year)){
                $affectYears[$j++] = $yearTmp;
            }

            if( $yearTmp == strval(Carbon::now()->year)){
                $totalaffect += $affect->montant;
            }

            $totalAffectation +=$affect->montant;
           
        }

        $restcaisse = $totalAlimentation - $totalAffectation;

        // filling Years array with all data
        $Years = array_fill(0, (count($alimYears) + count($affectYears)), 0);
        $Years = array_merge($alimYears, $affectYears);

        // removing duplicates & rearranging indexes
        $Years = array_unique($Years, SORT_STRING );
        // unsetting 0 from the array...
        if (($key = array_search( 0 , $Years)) !== false) {
            unset($Years[$key]);
        }
        // Sorting Years
        rsort($Years);

        // to use in the js
        $yearsJS = json_encode($Years);

        // filter collections
        $clients = Customer::all();
        $projets = Project::all();
        $provenances = Provenance::all();
        $methodes = Method::all();
        $sites = Site::all();
        $prestations = Prestation::all();
        $fournisseurs = Provider::all();
        $superviseurs = Supervisor::all();

        return view('vendor/voyager/journal', compact('superviseurs', 'sites', 'prestations', 'fournisseurs', 'methodes', 'provenances', 'projets', 'clients', 'affectation_by_bu', 'bu', 'Years', 'yearsJS', 'alim_chart', 'alimentations', 'affectations', 'totalalim', 'totalaffect', 'restcaisse','totalaffectNonDecaisse','etat','totalAlimentation','totalAffectation'));
    }

    // JOURNAL CHART

    public function getJournalChart(Request $request){
        $alim_chart = null;
        $affect_chart = null;
        $timeLine = null;

        if($request['date_debut'] != '' && $request['date_fin'] != ''){
            if($request['chartMode'] == 'auto'){
                if ( $request['numberOfDays'] <= 7 ){
                    return $this->daysChart($request);
                }
                else if ( $request['numberOfDays'] > 7 && $request['numberOfDays'] <= 42 ){
                    return $this->weeksChart($request);
                }
                else if ( $request['numberOfDays'] > 42 && $request['numberOfDays'] <= 365 ){
                    return $this->monthsChart($request);
                }
                else if ( $request['numberOfDays'] > 365 ){
                    return $this->yearsChart($request);
                }
            }
            else if ($request['chartMode'] == 'days'){
                return $this->daysChart($request);
            }
            else if ($request['chartMode'] == 'weeks'){
                return $this->weeksChart($request);
            }
            else if ($request['chartMode'] == 'months'){
                return $this->monthsChart($request);
            }
            else if ($request['chartMode'] == 'years'){
                return $this->yearsChart($request);
            }
        }
        else{
            // returning NULL JSON
            
            return Response()->json(array('affect_chart' => $affect_chart, 'alim_chart' => $alim_chart, 'timeLine' => $timeLine) );
        }
    }

    // affect logs refactored to use model funct
    public function affect_logs($current_affect){
        if($current_affect != null){
            foreach($current_affect as $af){
                $af->montant = number_format($af->montant,2,"."," ");
                $af->company_id = isset( $af->company->nom ) ? $af->company->nom : '-' ;
                $af->project_id = isset( $af->projects->titre ) ? $af->projects->titre : '-' ;
                $af->etat = ( $af->etat ) ? "Décaissé" : "Non décaissé" ;
            }
        }

        return $current_affect;
    }

    // alim logs refactored to use model funct
    public function alim_logs($current_alim){
        if($current_alim != null){
            foreach($current_alim as $al){
                $al->montant = number_format($al->montant,2,"."," ");
                $al->method_id = isset($al->methode->titre) ? $al->methode->titre : '-';
                $al->provenance_id = isset($al->provenance->nom) ? $al->provenance->nom : '-';
            }
        }

        return $current_alim;
    }

    // ================================================================
    // ================================================================

    public function filtred_data($request){
        
        $affect_filtred = Affectation::whereBetween('date_affectation', [$request['date_debut'], $request['date_fin']])->orderBy('date_affectation', 'DESC')->get();
        $alim_filtred = Alimentation::whereBetween('date_alimentation', [$request['date_debut'], $request['date_fin']])->orderBy('date_alimentation', 'DESC')->get();

        // fill affectation only FLAG
        $affect_only = false;
        
        // AFFECTATION 
        // =========================================================>
        // BU filter
        // ====================================>
        if($request['filtre_bu'] != 'none'){
            // filtering affectation
            foreach($affect_filtred as $key => $affect){
                if($affect['company_id'] != $request['filtre_bu']){
                    $affect_filtred->forget($key);
                }
            }
            // filtering alimentation
            $alim_filtred = null;
        }

        // CLIENT filter
        // ====================================>
        if($request['filtre_client'] != 'none'){
            foreach($affect_filtred as $key => $affect){
                if($affect['client_id'] != $request['filtre_client']){
                    $affect_filtred->forget($key);
                }
            }
            // filtering alimentation
            $alim_filtred = null;
        }

        // PROJET filter
        // ====================================>
        if($request['filtre_projet'] != 'none'){
            foreach($affect_filtred as $key => $affect){
                if($affect['project_id'] != $request['filtre_projet']){
                    $affect_filtred->forget($key);
                }
            }
            // filtering alimentation
            $alim_filtred = null;
        }

        // SITE filter
        // ====================================>
        if($request['filtre_site'] != 'none'){
            foreach($affect_filtred as $key => $affect){
                if($affect['site_id'] != $request['filtre_site']){
                    $affect_filtred->forget($key);
                }
            }
            // filtering alimentation
            $alim_filtred = null;
        }

        // PRESTATION filter
        // ====================================>
        if($request['filtre_prestation'] != 'none'){
            foreach($affect_filtred as $key => $affect){
                if($affect['prestation_id'] != $request['filtre_prestation']){
                    $affect_filtred->forget($key);
                }
            }
            // filtering alimentation
            $alim_filtred = null;
        }

        // FOURNISSEUR filter
        // ====================================>
        if($request['filtre_fournisseur'] != 'none'){
            foreach($affect_filtred as $key => $affect){
                if($affect['provider_id'] != $request['filtre_fournisseur']){
                    $affect_filtred->forget($key);
                }
            }
            // filtering alimentation
            $alim_filtred = null;
        }

        // SUPERVISEUR filter // affect_filtred can be rebuild with whereHasMorph query
        // ====================================>
        $filtred_sites = null;

        if($request['filtre_superviseur'] != 'none'){
            $filtred_sites = Site::where('supervisor_id', $request['filtre_superviseur'])->get();
            $affect_temp = [];

            foreach($affect_filtred as $key => $affect){
                foreach($filtred_sites as $site){
                    if($affect['site_id'] == $site->id){
                        $affect_temp[] = $affect;
                    }
                }
            }

            // redefining affect_filtred
            $affect_filtred = $affect_temp;

            // filtering alimentation
            $alim_filtred = null;
        }

        // ALIMENTATION 
        // =========================================================>
        // PROVENANCE filter
        // ====================================>
        if($request['filtre_provenance'] != 'none'){
            foreach($alim_filtred as $key => $alim){
                if($alim['provenance_id'] != $request['filtre_provenance']){
                    $alim_filtred->forget($key);
                }
            }
            // filtering alimentation
            $affect_filtred = null;
        }

        // METHODE filter
        // ====================================>
        if($request['filtre_methode'] != 'none'){
            foreach($alim_filtred as $key => $alim){
                if($alim['method_id'] != $request['filtre_methode']){
                    $alim_filtred->forget($key);
                }
            }
            // filtering alimentation
            $affect_filtred = null;
        }

        if($filtred_sites == null){
            $data = array('affect_filtred' => $affect_filtred, 'alim_filtred' =>  $alim_filtred);
        }
        else{
            $data = array('affect_filtred' => $affect_filtred, 'alim_filtred' =>  $alim_filtred, 'filtred_sites' => $filtred_sites);
        }

        return $data;
    }

    // ================================================================
    // ================================================================


    // DAYS duration chart
    public function daysChart(Request $request){
        // filling timeLine with nbrdays wanted 

        // adding an other entry if only 1 exists (for wire chart display)
        ($request['numberOfDays'] == 1) ? $nbrDays = $request['numberOfDays'] + 1 : $nbrDays = $request['numberOfDays'];
        $startingDate = Carbon::parse($request['date_debut']);

        $timeLine = array_fill(1, $nbrDays, 0);

        // filling months array based on the actual month beeing the last index value
        for($i = 1; $i <= $nbrDays; $i++){
            $timeLine[$i] = $startingDate->day($startingDate->day)->locale('fr')->isoFormat('LL');
            $startingDate->addDays(1);
        }

        //initialising arrays to 0
        $alim_month = array_fill(1, $nbrDays, 0);
        $affect_month = array_fill(1, $nbrDays, 0);

        // ************************************************

        // getting alimentations & affectations in the selected period with the selected filters
        $filtred_data = $this->filtred_data($request);

        $alim_chart = $filtred_data['alim_filtred'];
        $affect_chart = $filtred_data['affect_filtred'];

        if(isset($filtred_data['filtred_sites'])){
            $sites_provisor = $filtred_data['filtred_sites'];
        }else{
            $sites_provisor = null;
        }

        // ************************************************

        // filling assignments & supplies data arrays
        for($i = 1; $i <= $nbrDays; $i++){
            if($alim_chart != null){
                foreach($alim_chart as $alim){
                    if( Carbon::parse($alim->date_alimentation)->locale('fr')->isoFormat('LL') == $timeLine[$i] ){
                        $alim_month[$i] += $alim->montant;
                    }
                }
            }
            else{
                $alim_month[$i] = 0;
            }

            if($affect_chart != null){
                foreach($affect_chart as $affect){
                    if( Carbon::parse($affect->date_affectation)->locale('fr')->isoFormat('LL') == $timeLine[$i] ){
                        $affect_month[$i] += $affect->montant;
                    }
                }
            }
            else{
                $affect_month[$i] = 0;
            }
        }

        // ************************************************

        // Logs refactore
        $af_logs = $this->affect_logs($affect_chart);
        $al_logs = $this->alim_logs($alim_chart);

        return Response()->json(array('alim_month' => $alim_month, 'affect_month' => $affect_month, 'affect_chart' => $affect_chart, 'alim_chart' => $alim_chart, 'timeLine' => $timeLine, 'affectations_logs' => $af_logs, 'alimentations_logs' => $al_logs, 'sites_provisor' => $sites_provisor) );
    }

    // ================================================================

    // WEEKS duration chart
    public function weeksChart(Request $request){
        // filling timeLine with nbrweeks wanted

        $nbrWeeks = Carbon::parse($request['date_fin'])->diffInWeeks(Carbon::parse($request['date_debut']));
        // adding an other entry if only 1 exists (for wire chart display)
        ($nbrWeeks <= 1) ? $nbrWeeks++ : $nbrWeeks = $nbrWeeks;
        
        $startingDate = Carbon::parse($request['date_debut']);

        $timeLine = array_fill(1, $nbrWeeks, 0);

        // filling years array based on the actual month beeing the last index value  // format('W-o')
        for($i = 1; $i <= $nbrWeeks+1; $i++){
            $timeLine[$i] = $startingDate->week($startingDate->week)->format('W-o');
            $startingDate->addWeeks(1);
        }

        //initialising arrays to 0
        $alim_month = array_fill(1, $nbrWeeks+1, 0);
        $affect_month = array_fill(1, $nbrWeeks+1, 0);

        // ************************************************

        // getting alimentations & affectations in the selected period with the selected filters
        $filtred_data = $this->filtred_data($request);

        $alim_chart = $filtred_data['alim_filtred'];
        $affect_chart = $filtred_data['affect_filtred'];

        if(isset($filtred_data['filtred_sites'])){
            $sites_provisor = $filtred_data['filtred_sites'];
        }else{
            $sites_provisor = null;
        }

        // ************************************************

            // filling assignments & supplies data arrays
            for($i = 1; $i <= $nbrWeeks+1; $i++){
                if($alim_chart != null){
                    foreach($alim_chart as $alim){
                        if( Carbon::parse($alim->date_alimentation)->Format('W-o') == $timeLine[$i] ){
                            $alim_month[$i] += $alim->montant;
                        }
                    }
                }
                else{
                    $alim_month[$i] = 0;
                }

                if($affect_chart != null){
                    foreach($affect_chart as $affect){
                        if( Carbon::parse($affect->date_affectation)->Format('W-o') == $timeLine[$i] ){
                            $affect_month[$i] += $affect->montant;
                        }
                    }
                }
                else{
                    $affect_month[$i] = 0;
                }
            }
        // ************************************************

        // Logs refactore
        $af_logs = $this->affect_logs($affect_chart);
        $al_logs = $this->alim_logs($alim_chart);

        return Response()->json(array('alim_month' => $alim_month, 'affect_month' => $affect_month, 'affect_chart' => $affect_chart, 'alim_chart' => $alim_chart, 'timeLine' => $timeLine, 'affectations_logs' => $af_logs, 'alimentations_logs' => $al_logs, 'sites_provisor' => $sites_provisor) );
    }

    // ================================================================

    // MONTHS duration chart
    public function monthsChart(Request $request){
        // filling timeLine with nbrMonths wanted
        $nbrMonths = Carbon::parse($request['date_fin'])->diffInMonths(Carbon::parse($request['date_debut']));
        // adding an other entry if only 1 exists (for wire chart display)
        ($nbrMonths <= 1) ? $nbrMonths++ : $nbrMonths = $nbrMonths;

        $startingDate = Carbon::parse($request['date_debut']);

        $timeLine = array_fill(1, $nbrMonths, 0);

        // filling months array based on the actual month beeing the last index value
        for($i = 1; $i <= $nbrMonths+1; $i++){
            $timeLine[$i] = $startingDate->month($startingDate->month)->locale('fr')->isoFormat('MMM');
            $startingDate->addMonths(1);
        }

        //initialising arrays to 0
        $alim_month = array_fill(1, $nbrMonths+1, 0);
        $affect_month = array_fill(1, $nbrMonths+1, 0);

        // ************************************************

        // getting alimentations & affectations in the selected period with the selected filters
        $filtred_data = $this->filtred_data($request);

        $alim_chart = $filtred_data['alim_filtred'];
        $affect_chart = $filtred_data['affect_filtred'];

        if(isset($filtred_data['filtred_sites'])){
            $sites_provisor = $filtred_data['filtred_sites'];
        }else{
            $sites_provisor = null;
        }

        // ************************************************

        // filling assignments & supplies data arrays
        for($i = 1; $i <= $nbrMonths+1; $i++){
            if($alim_chart != null){
                foreach($alim_chart as $alim){
                    if( Carbon::parse($alim->date_alimentation)->locale('fr')->isoFormat('MMM') == $timeLine[$i] ){
                        $alim_month[$i] += $alim->montant;
                    }
                }
            }
            else{
                $alim_month[$i] = 0;
            }

            if($affect_chart != null){
                foreach($affect_chart as $affect){
                    if( Carbon::parse($affect->date_affectation)->locale('fr')->isoFormat('MMM') == $timeLine[$i] ){
                        $affect_month[$i] += $affect->montant;
                    }
                }
            }
            else{
                $affect_month[$i] = 0;
            }
        }

        // ************************************************

        // Logs refactore
        $af_logs = $this->affect_logs($affect_chart);
        $al_logs = $this->alim_logs($alim_chart);

        return Response()->json(array('alim_month' => $alim_month, 'affect_month' => $affect_month, 'affect_chart' => $affect_chart, 'alim_chart' => $alim_chart, 'timeLine' => $timeLine, 'affectations_logs' => $af_logs, 'alimentations_logs' => $al_logs, 'sites_provisor' => $sites_provisor) );
    }

    // ================================================================

    // YEARS duration chart
    public function yearsChart(Request $request){
        // filling timeLine with nbryears wanted 

        $nbrYears = (Carbon::parse($request['date_fin'])->year - Carbon::parse($request['date_debut'])->year) ;
        // adding an other entry if only 1 exists (for wire chart display)
        ($nbrYears <= 1) ? $nbrYears++ : $nbrYears = $nbrYears;

        $startingDate = Carbon::parse($request['date_debut']);

        $timeLine = array_fill(1, $nbrYears, 0);

        // filling years array based on the actual month beeing the last index value
        for($i = 1; $i <= $nbrYears+1; $i++){
            $timeLine[$i] = $startingDate->year;
            $startingDate->addYears(1);
        }

        //initialising arrays to 0
        $alim_month = array_fill(1, $nbrYears+1, 0);
        $affect_month = array_fill(1, $nbrYears+1, 0);

        // ************************************************

        // getting alimentations & affectations in the selected period with the selected filters
        $filtred_data = $this->filtred_data($request);

        $alim_chart = $filtred_data['alim_filtred'];
        $affect_chart = $filtred_data['affect_filtred'];

        if(isset($filtred_data['filtred_sites'])){
            $sites_provisor = $filtred_data['filtred_sites'];
        }else{
            $sites_provisor = null;
        }

        // ************************************************

        // filling assignments & supplies data arrays
        for($i = 1; $i <= $nbrYears+1; $i++){
            if($alim_chart != null){
                foreach($alim_chart as $alim){
                    if( Carbon::parse($alim->date_alimentation)->year == $timeLine[$i] ){
                        $alim_month[$i] += $alim->montant;
                    }
                }
            }
            else{
                $alim_month[$i] = 0;
            }

            if($affect_chart != null){
                foreach($affect_chart as $affect){
                    if( Carbon::parse($affect->date_affectation)->year == $timeLine[$i] ){
                        $affect_month[$i] += $affect->montant;
                    }
                }
            }
            else{
                $affect_month[$i] = 0;
            }
        }

        // ************************************************

        // Logs refactore
        $af_logs = $this->affect_logs($affect_chart);
        $al_logs = $this->alim_logs($alim_chart);

        return Response()->json(array('alim_month' => $alim_month, 'affect_month' => $affect_month, 'affect_chart' => $affect_chart, 'alim_chart' => $alim_chart, 'timeLine' => $timeLine, 'affectations_logs' => $af_logs, 'alimentations_logs' => $al_logs, 'sites_provisor' => $sites_provisor) );
    }

}