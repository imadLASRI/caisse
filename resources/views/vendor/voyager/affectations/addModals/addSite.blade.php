    
<div class="modal" id="siteModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nouveau Site</h4>
            </div>

            <div class="addModals" id="addSite">
                <form class="ModalForm" data-parsley-validate>
                    <div class="panel-body">
                        <div class="form-group  col-md-12 ">
                            <label class="control-label" for="nom_site">Nom</label>
                            <input type="text" class="form-control" id="nom_site" placeholder="Nom" value="" data-parsley-required >
                        </div>
                                                        
                        <div class="form-group  col-md-12 ">
                            <label class="control-label" for="superviseur_site">Superviseur</label>
                            <select class="form-control customSelect" id="superviseur_site" name="superviseur_site" data-parsley-required >
                                <option value="">Sélectionnez un superviseur</option>
                                @foreach($supervisors as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group  col-md-12 ">
                            <label class="control-label" for="budget_site">Budget</label>
                            <input type="text" class="form-control" id="budget_site" placeholder="Budget" value="" data-parsley-required data-parsley-type='number'>
                        </div>
                    </div>

                    <!-- panel-body -->

                    <div class="panel-footer">
                        <button data-model="site" type="submit" class="btn btn-primary save modalSubmit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
