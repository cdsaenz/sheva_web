

       <div class="container-fluid">
           <div class="row">
               <div class="col-xs-12 col-lg-8 mx-auto">
                    <div class="card mt-3">
                      <div class="card-header cs-view-header">
                          <h4><i class="fas fa-user-check mr-2"></i><?= $title ?></h4>
                          <p class="small"><?= _("Informacion Archivo") ?></p>
                          <p><?= _("Para: ") ?><?= $related ?></p>
                       </div>

                      <div class="card-body cs-view-body">
                          <?= form_open("media/dodelete","id='mainForm' class='form-horizontal md-form'",$hidden) ?>

                          <div class="form-group row mb-0">
                              <label for="media_type" class="col-sm-4 control-label"><?= _("Tipo de Medios/Tipo de Origen") ?></label>
                              <div class="col-sm-8">
                      		    <p class="form-control-static"><?= $row['media_type'] ?> <?= $row['src_type'] ?></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="media_name" class="col-sm-4 control-label"><?= _("Descripcion o Nombre") ?></label>
                              <div class="col-sm-8">
                      		    <p class="form-control-static"><?= $row['media_name'] ?></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="media_src" class="col-sm-4 control-label"><?= _("Fuente") ?></label>
                              <div class="col-sm-8">
                          		    <p class="form-control-static"><?= $row['media_src'] ?></p>
                              </div>
                          </div>

                          <!--- SAVE BUTTON OUT OF THE TABS !-->
                          <div class="form-group row mb-2 mt-3">
                              <div class="col-sm-4 offset-4">
                                  <button type="submit" id="submitButton" class="btn btn-danger"><?= _("CONFIRMA BORRADO") ?></button>
                              </div>
                          </div>

                          <?= form_close() ?>


                      </div>
                    </div>
               </div>
           </div>
       </div>
