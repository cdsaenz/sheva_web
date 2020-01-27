

       <div class="container-fluid">
           <div class="row">
               <div class="col-xs-12 col-lg-8 mx-auto">
                    <div class="card mt-3">
                      <div class="card-header cs-view-header">
                            <h4><i class="fas fa-tools mr-2"></i><?= $title ?></h4>
                            <p class="small"><?= _("Ingrese los datos del Codigo de tabla") ?></p>

                            <div class="row">
                                <div class="col mx-auto text-center">
                                  <a href="<?= base_url("codes/edit/$id") ?>" class="btn btn-dark float-center action-button3">
                                      <i class="fas fa-pen-square"></i>
                                      <div class="small"><?= _("Editar") ?></div>
                                  </a>
                                  <a href="<?= base_url("codes") ?>" class="btn btn-dark float-center action-button3">
                                        <div class="fas fa-list"></div>
                                        <div class="small"><?= _("Listado") ?></div>
                                  </a>
                                </div>
                            </div>
                       </div>
                      <div class="card-body cs-view-body">
                          <?= form_open("#","class=form-horizontal",$hidden) ?>

                          <div class="form-group row mb-0">
                              <label for="code_field" class="col-sm-4 control-label"><?= _("Tipo de tabla") ?></label>
                              <div class="col-sm-8">
                      		    <p class="form-control-static"><?= $row['code_field'] ?></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="code_value" class="col-sm-4 control-label"><?= _("Codigo de tabla") ?></label>
                              <div class="col-sm-8">
                      		    <p class="form-control-static"><?= $row['code_value'] ?></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="code_number" class="col-sm-4 control-label"><?= _("Orden en Lista") ?></label>
                              <div class="col-sm-8">
                          		    <p class="form-control-static"><?= $row['code_number'] ?></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="code_text" class="col-sm-4 control-label"><?= _("Texto opcional") ?></label>
                              <div class="col-sm-8">
                          		    <p class="form-control-static"><?= $row['code_text'] ?></p>
                              </div>
                          </div>


                          <?= form_close() ?>


                      </div>
                    </div>
               </div>
           </div>
       </div>
