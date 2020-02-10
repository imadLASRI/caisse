<div class="modal" id="fournisseurModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nouveau Fournisseur</h4>
        </div>

        <div class="addModals" id="addFournisseur"> 
            <form class="ModalForm" data-parsley-validate>
                <div class="panel-body">
                    <div class="form-group  col-md-12 ">
                        <label class="control-label" for="name">Nom</label>
                        <input type="text" class="form-control" id="nom_fournisseur" placeholder="Nom" value="" data-parsley-required>
                    </div>
                                                    
                    <div class="form-group  col-md-12 ">
                        <label class="control-label" for="telephone_fournisseur">Telephone</label>
                        <input type="text" class="form-control" id="telephone_fournisseur" placeholder="Telephone" value="" data-parsley-required data-parsley-type='number'>
                    </div>
                                                    
                    <div class="form-group  col-md-12 ">
                        <label class="control-label" for="email_fournisseur">Email</label>
                        <input type="text" class="form-control" id="email_fournisseur" placeholder="Email" value="" data-parsley-required data-parsley-type='email'>
                    </div>
                                                    
                    <div class="form-group  col-md-12 ">
                        <label class="control-label" for="adresse_fournisseur">Adresse</label>
                        <textarea class="form-control" id="adresse_fournisseur" rows="3" data-parsley-required></textarea>
                    </div>
                                                    
                    <div class="form-group  col-md-12 ">
                        <label class="control-label" for="ice_fournisseur">Ice</label>
                        <input type="text" class="form-control" id="ice_fournisseur" placeholder="Ice" value="" data-parsley-required data-parsley-type='number'>
                    </div>
                </div>

                <!-- panel-body -->

                <div class="panel-footer">
                    <button data-model="fournisseur" type="submit" class="btn btn-primary modalSubmit">Enregistrer</button>
                </div>
            </form>
        </div>

      </div>
    </div>
  </div>
