<div class="modal" id="prestationModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nouvelle Prestation</h4>
        </div>

        <div class="addModals" id="addPrestation">
          <form class="ModalForm" data-parsley-validate>
            <div class="panel-body">
                <div class="form-group  col-md-12 ">
                    <label class="control-label" for="name">Titre</label>
                    <input type="text" class="form-control" id="titre_prestation" placeholder="Titre" value="" data-parsley-required>
                </div>

                <div class="form-group  col-md-12 ">
                    <label class="control-label" for="name">Descriptif</label>
                    <textarea class="form-control" id="descriptif_prestation" rows="3" data-parsley-required></textarea>
                </div>
            </div>
            
            <!-- panel-body -->

            <div class="panel-footer">
                <button data-model="prestation" type="submit" class="btn btn-primary save modalSubmit">Enregistrer</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
