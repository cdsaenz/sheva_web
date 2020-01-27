

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
                      <div class="card-header cs-edit-header">
                           <?php if ($hidden["edit_mode"] == 'edit') :  ?>
                               <h4><i class="fas fa-book mr-2"></i>Cliente: <?= $row['cust_name'] ?></h4>
                           <?php else : ?>
                               <h4><i class="fas fa-book mr-2"></i><?= _("Nuevo Cliente") ?></h4>
                               <p class="small">Ingrese los datos para el nuevo cliente</p>
                           <?php endif ?>
                      </div>
                      <div class="card-body cs-edit-body">
                          <!--- MULTITAB FORM !-->
                          <?= form_open("registrations/doeval","id='mainForm' class='form-horizontal md-form needs-validation' novalidate",$hidden) ?>
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
                                <label for="reg_status" class="col-sm-4 control-label"><?= _("Tipo solicitud") ?></label>
                                    <div class="col-sm-8">
                                           <p class="form-control-static badge badge-primary p-2"><?= $this->app->get_code_label("reg_type",$row['reg_type']) ?></p>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                <label for="reg_status" class="col-sm-4 control-label font-weight-bold text-danger"><?= _("Estado (APROBADO procesa)") ?></label>
                                <div class="col-sm-8">
                                  <?= form_dropdown('reg_status', $this->app->get_select_values("reg_status"), $row['reg_status'], "class='form-control' required"); ?>
                                      <div class="invalid-feedback">
                                          <p><?= _("Indicar si se aprueba (o rechaza) la petición.") ?></p>
                                      </div>
                                </div>
                                </div>

                                <div class="form-group row mb-2">
                                   <label for="usr_email_addr" class="col-sm-4 control-label"><?= _("Email Usuario") ?></label>
                                   <div class="col-sm-8">
                                     <input class="form-control" name="usr_email_addr" id="usr_email_addr" placeholder="<?= _("Email usuario") ?>" value="<?= $row['usr_email_addr'] ?>" required>
                                      <div class="invalid-feedback">
                                          <p><?= _("Debe ingresar Email") ?></p>
                                      </div>
                                   </div>
                                </div>

                                <div class="form-group row mb-1">
                                <label for="cust_name" class="col-sm-4 control-label"><?= _("Nombre o Razon Social") ?></label>
                                    <div class="col-sm-8">
                                       <input class="form-control" name="cust_name" value="<?= $row['cust_name'] ?>"
                                             placeholder="<?= _("Nombre de Cliente") ?>" required/>
                                       <div class="invalid-feedback">
                                           <p><?= _("Indicar Nombre o Razon Social.") ?></p>
                                       </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                 <label for="price_list_id" class="col-sm-4 control-label font-weight-bold"><?= _("Lista de Precios asignada") ?></label>
                                 <div class="col-sm-8">
                                   <?= form_dropdown('price_list_id', ["1"=>"1","2"=>"2","3"=>"3","4"=>"4"],"", "id='price_list_id' class='form-control' required"); ?>
                                   <div class="invalid-feedback">
                                       <p><?= _("Ingrese lista a asignar.") ?></p>
                                   </div>
                                 </div>
                                 </div>

                                <div class="form-group row mb-2">
                                   <label for="tax_id" class="col-sm-4 control-label"><?= _("CUIT") ?></label>
                                   <div class="col-sm-8">
                                     <input class="form-control" name="tax_id" id="tax_id" placeholder="<?= _("CUIT, solo necesario para NUEVOS CLIENTES") ?>" value="<?= $row['tax_id'] ?>">
                                      <div class="invalid-feedback">
                                          <p><?= _("Debe ingresar CUIT") ?></p>
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
                                  <button type="submit" id="submitButton" class="btn btn-primary"><?= _("Procesar") ?></button>
                              </div>
                          </div>

                          <?= form_close() ?>

                      </div>
                    </div>
               </div>
           </div>
       </div>

<!-- JavaScript en bottom view -->
