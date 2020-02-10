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
use App\Alimentation;
use App\Affectation;

class AlimentationController extends VoyagerBaseController
{
    use BreadRelationshipParser;

    //***************************************
    //                _____
    //               |  __ \
    //               | |  | |
    //               | |  | |
    //               | |__| |
    //               |_____/
    //
    //         Delete an item BREA(D)
    //
    //****************************************

    public function destroy(Request $request, $id)
    {
        //added this to share with all views

        //TOTAL CAISSE
        $alimentationTab = Alimentation::all();
        $affectationTab = Affectation::all();
        $caisseTotal = 0;
        foreach($alimentationTab as $alimentationItem){
            $caisseTotal += $alimentationItem->montant;
        }
        foreach($affectationTab as $affectationItem){
            $caisseTotal -= $affectationItem->montant;
        }

        // test b4 deleting
        $alimentation_check = Alimentation::find($id);
        
        if( $alimentation_check->montant > $caisseTotal){
            return Redirect()->back();
        }
        // Old destroy code if "not decaissé"
        else{
            $slug = $this->getSlug($request);

            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // Check permission
            $this->authorize('delete', app($dataType->model_name));

            // Init array of IDs
            $ids = [];
            if (empty($id)) {
                // Bulk delete, get IDs from POST
                $ids = explode(',', $request->ids);
            } else {
                // Single item delete, get ID from URL
                $ids[] = $id;
            }
            foreach ($ids as $id) {
                $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

                $model = app($dataType->model_name);
                if (!($model && in_array(SoftDeletes::class, class_uses_recursive($model)))) {
                    $this->cleanup($dataType, $data);
                }
            }

            $displayName = count($ids) > 1 ? $dataType->getTranslatedAttribute('display_name_plural') : $dataType->getTranslatedAttribute('display_name_singular');

            $res = $data->destroy($ids);
            $data = $res
                ? [
                    'message'    => __('voyager::generic.successfully_deleted')." {$displayName}",
                    'alert-type' => 'success',
                ]
                : [
                    'message'    => __('voyager::generic.error_deleting')." {$displayName}",
                    'alert-type' => 'error',
                ];

            if ($res) {
                event(new BreadDataDeleted($dataType, $data));
            }

            return redirect()->route("voyager.{$dataType->slug}.index")->with($data);
        }
    }

    public function restore(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('delete', app($dataType->model_name));

        // Get record
        $model = call_user_func([$dataType->model_name, 'withTrashed']);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        $data = $model->findOrFail($id);

        $displayName = $dataType->getTranslatedAttribute('display_name_singular');

        $res = $data->restore($id);
        $data = $res
            ? [
                'message'    => __('voyager::generic.successfully_restored')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('voyager::generic.error_restoring')." {$displayName}",
                'alert-type' => 'error',
            ];

        if ($res) {
            event(new BreadDataRestored($dataType, $data));
        }

        return redirect()->route("voyager.{$dataType->slug}.index")->with($data);
    }

}
