<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerController;
use TCG\Voyager\Facades\Voyager;

// Custom includes
// ==========================================================>
use Carbon\Carbon;

use App\Company;
use App\Affectation;
use App\Alimentation;

class DashboardController extends VoyagerController
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
        $latestAssignment = Affectation::all()->sortByDesc('date_affectation')->take(3);
        $latestSupply = Alimentation::all()->sortByDesc('date_alimentation')->take(3);        
        $affectationsDecaisse = Affectation::where('etat', '1')->get();
        $affectationsNonDecaisse = Affectation::where('etat', '0')->get();
        $affectations = Affectation::all();
        $alimentations = Alimentation::all();        
        $bu = Company::all()->count();

        // ==============================================================>>
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
        
        // ==============================================================>>
       
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

        return view('vendor/voyager/index', compact('affectation_by_bu', 'bu', 'Years', 'alim_chart', 'latestAssignment', 'latestSupply', 'alimentations', 'affectations', 'totalalim', 'totalaffect', 'restcaisse','totalaffectNonDecaisse','etat','totalAlimentation','totalAffectation'));
    }
    
    public function getChartData(Request $request){
        // $alim_chart = Alimentation::all()->sortByDesc('date_alimentation');
        // $affect_chart = Affectation::all()->sortByDesc('date_affectation');
        $now = Carbon::now();

        $alim_month = array_fill(1, 12, 0);
        $affect_month = array_fill(1, 12, 0);
        $months = array_fill(1, 12, 0);

        // get data depending on the selected year
        if($request['dataYear'] != 'none' ){
            $selectedYear = $request['dataYear'];
            // defining start and end date (for the months rotation -> to get prev months of last year)
            $start_date = Carbon::now()->year($selectedYear)->month(1)->day(1)->toDateString();
            $end_date = Carbon::parse($start_date)->copy()->month(12)->day(31)->toDateString();

            // DB request
            $alim_chart = Alimentation::whereBetween('date_alimentation',[$start_date, $end_date])->orderBy('date_alimentation', 'DESC')->get();
            $affect_chart = Affectation::whereBetween('date_affectation',[$start_date, $end_date])->orderBy('date_affectation', 'DESC')->get();

            // define $months to 1 straight year
            $year_months = new Carbon();
            $year_months->month(0);
            for($i = 1; $i <= 12; $i++){
                $months[$i] = $year_months->addMonthWithNoOverflow()->locale('fr')->isoFormat('MMM');
            }
        }
        else{
            // defining start and end date (for the months rotation -> to get prev months of last year)
            $start_date = Carbon::now()->subYearNoOverflow()->addMonthNoOverflow()->day(1)->toDateString();
            $end_date = $now->toDateString();

            // DB request
            $alim_chart = Alimentation::whereBetween('date_alimentation',[$start_date, $end_date])->orderBy('date_alimentation', 'DESC')->get();
            $affect_chart = Affectation::whereBetween('date_affectation',[$start_date, $end_date])->orderBy('date_affectation', 'DESC')->get();

            // redefining months based on the actual month beeing the last index value
            for($i = 1; $i <= 12; $i++){
                $months[$i] = $now->addMonthWithNoOverflow()->locale('fr')->isoFormat('MMM');
            }
        }

        // filling assignments & supplies data arrays after the filters application
        for($i = 1; $i <= 12; $i++){
            foreach($alim_chart as $alim){
                if( $months[$i] == (Carbon::parse($alim->date_alimentation)->locale('fr')->isoFormat('MMM'))){
                    $alim_month[$i] += $alim->montant;
                }
            }
            foreach($affect_chart as $affect){
                if( $months[$i] == (Carbon::parse($affect->date_affectation)->locale('fr')->isoFormat('MMM')) ){
                    $affect_month[$i] += $affect->montant;
                }
            }
        }

        return Response()->json(array('alim_month' => $alim_month, 'affect_month' => $affect_month, 'months' => $months) );
    }

}
