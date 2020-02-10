<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\Customer;
use App\Site;
use App\Prestation;
use App\Provider;

class ModalsController extends Controller
{
    public function storeModalData(Request $request){

        if($request['dataModel'] == 'client'){
            $data = Customer::create([
                'nom' => $request['nom'],
                'telephone' => $request['telephone'],
                'email' => $request['email'],
                'adresse' => $request['adresse'],
                'ice' => $request['ice']
            ]);
        }
        else if($request['dataModel'] == 'projet'){
            $data = Project::create([
                'titre' => $request['titre'],
                'customer_id' => $request['client_id']
            ]);
        }
        else if($request['dataModel'] == 'site'){
            $data = Site::create([
                'nom' => $request['nom'],
                'budget' => $request['budget'],
                'project_id' => $request['project_id'],
                'supervisor_id' => $request['supervisor_id'],
            ]);
        }
        else if($request['dataModel'] == 'prestation'){
            $data = Prestation::create([
                'titre' => $request['titre'],
                'descriptif' => $request['descriptif'],
                'site_id' => $request['site_id'],
            ]);
        }
        else if($request['dataModel'] == 'fournisseur'){
            $data = Provider::create([
                'nom' => $request['nom'],
                'telephone' => $request['telephone'],
                'email' => $request['email'],
                'ice' => $request['ice'],
                'prestation_id' => $request['prestation_id'],
            ]);
        }

        return Response()->json($data);
    }
}
