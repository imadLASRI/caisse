<div class="modal" id="projetModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nouveau Projet</h4>
        </div>

        <div class="addModals" id="addProjet">
          <form class="ModalForm" data-parsley-validate>
            <div class="panel-body">
                <div class="form-group col-md-12 ">
                    <label class="control-label" for="titre">Titre</label>
                    <input type="text" class="form-control" name="titre" id="titre_projet" placeholder="Titre" value="" data-parsley-required data-parsley-maxlength="100">
                </div>
            </div>

            <div class="panel-footer">
                <button data-model="projet" type="submit" class="btn btn-primary save modalSubmit">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>