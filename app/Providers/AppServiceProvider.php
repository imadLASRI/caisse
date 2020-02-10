<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Alimentation;
use App\Affectation;

// including blade to alias the modalviews
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('path.public', function() {
        //     return base_path('../public_html/barea_caisse');
        // });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        // modal views alias
        Blade::include('voyager::affectations.addModals.addClient', 'addClient');
        Blade::include('voyager::affectations.addModals.addProjet', 'addProjet');
        Blade::include('voyager::affectations.addModals.addSite', 'addSite');
        Blade::include('voyager::affectations.addModals.addPrestation', 'addPrestation');
        Blade::include('voyager::affectations.addModals.addFournisseur', 'addFournisseur');

        //added this to share with all views

        //TOTAL CAISSE
        $alimentationTab = Alimentation::all();
        $affectationTab = Affectation::all();
        $sharedCaisse = 0;
        foreach($alimentationTab as $alimentationItem){
            $sharedCaisse += $alimentationItem->montant;
        }
        foreach($affectationTab as $affectationItem){
            $sharedCaisse -= $affectationItem->montant;
        }
        View::share('sharedCaisse', $sharedCaisse);
    }
}
