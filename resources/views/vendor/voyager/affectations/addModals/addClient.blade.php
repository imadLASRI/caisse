<div class="modal" id="clientModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nouveau Client</h4>
        </div>

        <div class="addModals" id="addClient">
          <form class="ModalForm" data-parsley-validate>
            <div class="panel-body">
                <div class="form-group col-md-12 ">
                    <label class="control-label" for="name">Nom</label>
                    <input type="text" class="form-control" id="nom_client" placeholder="Nom" value="" data-parsley-required data-parsley-maxlength="100">
                </div>
                                                    
                <div class="form-group  col-md-12 ">
                    <label class="control-label" for="name">Telephone</label>
                    <input type="text" class="form-control" id="telephone_client" placeholder="Telephone" value="" data-parsley-required data-parsley-type='number'>
                </div>
                                                    
                <div class="form-group  col-md-12 ">
                    <label class="control-label" for="name">Email</label>
                    <input type="text" class="form-control" id="email_client" placeholder="Email" value="" data-parsley-required data-parsley-type='email'>
                </div>
                                                    
                <div class="form-group  col-md-12 ">
                    <label class="control-label" for="name">Adresse</label>
                    <textarea class="form-control" id="adresse_client" rows="5" data-parsley-required></textarea>
                </div>
                                                
                <div class="form-group  col-md-12 ">
                    <label class="control-label" for="name">Ice</label>
                    <input type="text" class="form-control" id="ice_client" placeholder="Ice" value="" data-parsley-required data-parsley-type='number'>
                </div>
            </div>

            <div class="panel-footer">
                <button data-model="client" type="submit" class="btn btn-primary save modalSubmit">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- data-dismiss="modal"  -->