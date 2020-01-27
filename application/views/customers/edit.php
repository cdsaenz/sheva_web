

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
                          <?= form_open("customers/doedit","id='mainForm' class='form-horizontal md-form needs-validation' novalidate",$hidden) ?>
                          <!--- TAB CAPTIONS !-->
                          <ul class="nav nav-tabs cs-edit-tabs" id="topTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true">
                              <?= _("Principal") ?></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="addr-tab" data-toggle="tab" href="#addr" role="tab" aria-controls="addr" aria-selected="true">
                              <?= _("Direccion") ?></a>
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
                                <label for="cust_name" class="col-sm-4 control-label"><?= _("Nombre o Razon Social") ?></label>
                                    <div class="col-sm-8">
                                       <input class="form-control" name="cust_name" value="<?= $row['cust_name'] ?>"
                                             placeholder="<?= _("Nombre de Cliente") ?>" required/>
                                       <div class="invalid-feedback">
                                           <p><?= _("Indicar Nombre o Razon Social.") ?></p>
                                       </div>
                                    </div>
                                </div>


                                <div class="form-group row mb-2">
                                <label for="cust_is_disabled" class="col-sm-4 control-label font-weight-bold"><?= _("Suspendido") ?></label>
                                <div class="col-sm-8">
                        			    <?= form_dropdown('cust_is_disabled', $this->customers_model->get_yn_values(true), $row['cust_is_disabled'], "class='form-control' required"); ?>
                                      <div class="invalid-feedback">
                                          <p><?= _("Indicar si el Cliente esta suspendido o no.") ?></p>
                                      </div>
                                </div>
                                </div>

                               <div class="form-group row mb-2">
                                <label for="cust_type" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo o Familia") ?></label>
                                <div class="col-sm-8">
                        			    <?= form_dropdown('cust_type', $this->customers_model->get_select_values("cust_type",true), $row['cust_type'], "id='cust_type' class='form-control' required"); ?>
                                      <div class="invalid-feedback">
                                          <p><?= _("Ingrese tipo de Cliente") ?></p>
                                      </div>
                                </div>
                                </div>

                                <div class="form-group row mb-2">
                                   <label for="tax_id" class="col-sm-4 control-label"><?= _("CUIT") ?></label>
                                   <div class="col-sm-8">
                                     <input class="form-control" name="tax_id" id="tax_id" placeholder="<?= _("CUIT") ?>" value="<?= $row['tax_id'] ?>" required>
                                      <div class="invalid-feedback">
                                          <p><?= _("Debe ingresar CUIT") ?></p>
                                      </div>
                                   </div>
                                </div>

                            </div>

                            <!--- ADDRESS TAB !-->
                            <div class="tab-pane fade mt-3 ml-2" id="addr" role="tabpanel" aria-labelledby="addr-tab">

                                <div class="form-group row mb-1">
                                <label for="cust_cod" class="col-sm-4 control-label"><?= _("Direccion") ?></label>
                                    <div class="col-sm-4">
                                      <input class="form-control" name="cust_addr1" value="<?= $row['cust_addr1'] ?>"
                                             placeholder="<?= _("Direccion 1") ?>"/>
                                    </div>
                                    <div class="col-sm-4">
                                      <input class="form-control" name="cust_addr2" value="<?= $row['cust_addr2'] ?>"
                                             placeholder="<?= _("Direccion 2") ?>"/>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                    <div class="col-sm-2 offset-4">
                                      <input class="form-control" name="addr_zip" value="<?= $row['addr_zip'] ?>"
                                             placeholder="<?= _("C.Postal") ?>"/>
                                    </div>
                                    <div class="col-sm-6">
                                      <input class="form-control" name="addr_city" value="<?= $row['addr_city'] ?>"
                                             placeholder="<?= _("Ciudad") ?>"/>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                <label for="cust_cod" class="col-sm-4 control-label"><?= _("Telefonos") ?></label>
                                    <div class="col-sm-4">
                                      <input class="form-control" name="phone_nbr1" value="<?= $row['phone_nbr1'] ?>"
                                             placeholder="<?= _("Telefono 1") ?>"/>
                                    </div>
                                    <div class="col-sm-4">
                                      <input class="form-control" name="phone_nbr2" value="<?= $row['phone_nbr2'] ?>"
                                             placeholder="<?= _("Telefono 2") ?>"/>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                <label for="cust_cod" class="col-sm-4 control-label"><?= _("Internet") ?></label>
                                    <div class="col-sm-4">
                                      <input class="form-control" name="email_addr" value="<?= $row['email_addr'] ?>"
                                             placeholder="<?= _("Email") ?>"/>
                                    </div>
                                    <div class="col-sm-4">
                                      <input class="form-control" name="www" value="<?= $row['www'] ?>"
                                             placeholder="<?= _("Pagina Web") ?>"/>
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

<!-- JavaScript en bottom view -->
