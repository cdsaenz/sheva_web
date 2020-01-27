

       <div class="container-fluid">
             <div class="row">
                <div class="col-xs-12 col-lg-12 text-center loading" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-sm-6 col-lg-6 alert mx-auto text-center alert-danger m-5 p-2" id="errors" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-xs-12 col-lg-8 mx-auto">
                    <div class="card mt-1 shadow">
                      <div class="card-header bg-dark text-white">
                           <?php if ($hidden["edit_mode"] == 'edit') :  ?>
                               <h4><i class="fas fa-cog mr-2"></i><?= $row['type_name'] ?>
                               </h4>
                              <p class="font-weight-bold"><?= $row['supp_name'] ?></p>
                           <?php else : ?>
                               <h4><i class="fas fa-cog mr-2"></i><?= _("Nuevo Tipo de Item") ?></h4>
                               <p class="small">Ingrese los datos para el nuevo tipo de item</p>
                           <?php endif ?>
                      </div>
                      <div class="card-body cs-edit-body">

                          <!--- MULTITAB FORM !-->
                          <?= form_open("itypes/doedit","id='mainForm' class='form-horizontal md-form needs-validation' novalidate",$hidden) ?>
                          <!--- TAB CAPTIONS !-->
                          <ul class="nav nav-tabs cs-edit-tabs" id="topTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true">
                              <?= _("Principal") ?></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">
                              <i class="fas fa-sticky-note"></i></a>
                            </li>
                          </ul>
                          <!--- CONTENT !-->
                          <div class="tab-content" id="topTabContent">
                            <!--- MAIN TAB !-->
                            <div class="tab-pane fade show active mt-3 ml-2" id="main" role="tabpanel" aria-labelledby="main-tab">
                                <div class="form-group row mb-1">
                                <label for="type_name" class="col-sm-4 control-label"><?= _("Nombre Tipo de Item") ?></label>
                                    <div class="col-sm-8">
                                       <input class="form-control" name="type_name" value="<?= $row['type_name'] ?>"
                                             placeholder="<?= _("Nombre Tipo de Item") ?>" required/>
                                       <div class="invalid-feedback">
                                           <p><?= _("Indicar nombre tipo de item.") ?></p>
                                       </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                <label for="type_is_disabled" class="col-sm-4 control-label font-weight-bold"><?= _("Suspendido") ?></label>
                                    <div class="col-sm-8">
                            			    <?= form_dropdown('type_is_disabled', $this->itypes_model->get_yn_values(true), $row['type_is_disabled'], "class='form-control' required"); ?>
                                          <div class="invalid-feedback">
                                              <p><?= _("Indicar si el item esta suspendido o no.") ?></p>
                                          </div>
                                    </div>
                                </div>


                            </div>


                            <!--- NOTES TAB !-->
                            <div class="tab-pane fade mt-3 ml-2" id="notes" role="tabpanel" aria-labelledby="notes-tab">

                                <div class="form-group row mb-2">
                                  <label for="notes" class="col-sm-4 control-label"><?= _("Notas") ?></label>
                                  <div class="col-sm-12">
                                      <textarea id="notes" name="notes" class="form-control" rows="5"><?= $row['notes'] ?></textarea>
                                  </div>
                                </div>
                            </div>
                          </div>

                          <!--- SAVE BUTTON OUT OF THE TABS !-->
                          <div class="form-group row mb-2 mt-3">
                              <div class="col-sm-4 offset-4">
                                  <button type="submit" id="submitButton" class="btn btn-primary"><?= _("Guardar") ?></button>
                              </div>
                          </div>

                          <?= form_close() ?>

                      </div>
                    </div>
               </div>
           </div>
       </div>
