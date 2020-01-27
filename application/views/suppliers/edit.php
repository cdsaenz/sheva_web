

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
                           <div class="row">
                             <?php if ($hidden["edit_mode"] == 'edit') :  ?>
                                  <div class="col-xs-12 col-md-2 text-center mx-auto my-auto">
                                    <img src="<?=$logo ?>" class="img-thumbnail shadow img-fluid" style="width: 100px; height: 100px" alt="Logo" />
                                    <div class="caption center-block small p-1 mt-1 mb-2">Logo</div>
                                  </div>
                                  <div class="col-xs-12 col-md-10 text-center mx-auto my-auto">
                                    <h4><i class="fas fa-shopping-cart mr-2"></i><?= $row['supp_name'] ?></h4>
                                    <p class="small"><?=_("Codigo de Distribuidor:") . $row['supp_cod'] ?></p>
                                  </div>
                             <?php else : ?>
                                 <div class="col-xs-12 text-center mx-auto my-auto">
                                    <h4><i class="fas fa-shopping-cart mr-2"></i><?= _("Nuevo Distribuidor") ?></h4>
                                    <p class="small">Ingrese los datos para el nuevo distribuidor</p>
                                 </div>
                             <?php endif ?>
                           </div>
                      </div>

                      <div class="card-body cs-edit-body">
                          <!--- MULTITAB FORM !-->
                          <?= form_open_multipart("suppliers/doedit","id='mainForm' class='form-horizontal needs-validation' novalidate",$hidden) ?>
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
                                <label for="supp_cod" class="col-sm-4 control-label"><?= _("Codigo") ?></label>
                                    <div class="col-sm-8">
                                      <input class="form-control" name="supp_cod" value="<?= $row['supp_cod'] ?>"
                                             placeholder="<?= _("Codigo de proveedor") ?>" required/>
                                       <div class="invalid-feedback">
                                           <p><?= _("Indicar Codigo del proveedor.") ?></p>
                                       </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                <label for="supp_name" class="col-sm-4 control-label"><?= _("Nombre o Razon Social") ?></label>
                                    <div class="col-sm-8">
                                       <input class="form-control" name="supp_name" value="<?= $row['supp_name'] ?>"
                                             placeholder="<?= _("Nombre de Proveedor") ?>" required/>
                                       <div class="invalid-feedback">
                                           <p><?= _("Indicar Nombre o Razon Social.") ?></p>
                                       </div>
                                    </div>
                                </div>


                                <div class="form-group row mb-2">
                                <label for="supp_is_disabled" class="col-sm-4 control-label font-weight-bold"><?= _("Suspendido") ?></label>
                                <div class="col-sm-8">
                        			    <?= form_dropdown('supp_is_disabled', $this->suppliers_model->get_yn_values(true), $row['supp_is_disabled'], "class='form-control' required"); ?>
                                      <div class="invalid-feedback">
                                          <p><?= _("Indicar si el proveedor esta suspendido o no.") ?></p>
                                      </div>
                                </div>
                                </div>

                               <div class="form-group row mb-2">
                                <label for="supp_type" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo o Familia") ?></label>
                                <div class="col-sm-8">
                        			    <?= form_dropdown('supp_type', $this->suppliers_model->get_select_values("supp_type",true), $row['supp_type'], "id='supp_type' class='form-control' required"); ?>
                                      <div class="invalid-feedback">
                                          <p><?= _("Ingrese tipo de proveedor") ?></p>
                                      </div>
                                </div>
                                </div>

                                <div id="upload_section" style="display: visible">
                                  <div class="form-group row mb-2">
                                     <div class="col-12 col-sm-4">
                                         <label for="file_name"><?= _("Logo del proveedor") ?></label>
                                     </div>
                                     <div class="col-7 col-sm-6">
                                         <input name="file_name" class="form-control" id="file_name" readonly placeholder="<?= _("Nombre Archivo") ?>">
                                     </div>
                                     <div class="col-5 col-sm-2">
                                         <label class="btn btn-primary form-control">
                                              <?= _("Buscar") ?>
                                              <input type="file" class="custom-file-input"  name="file_media_src" hidden>
                                         </label>
                                     </div>
                                  </div>
                                </div>

                            </div>

                            <!--- ADDRESS TAB !-->
                            <div class="tab-pane fade mt-3 ml-2" id="addr" role="tabpanel" aria-labelledby="addr-tab">

                                <div class="form-group row mb-1">
                                <label for="supp_cod" class="col-sm-4 control-label"><?= _("Direccion") ?></label>
                                    <div class="col-sm-4">
                                      <input class="form-control" name="supp_addr1" value="<?= $row['supp_addr1'] ?>"
                                             placeholder="<?= _("Direccion 1") ?>"/>
                                    </div>
                                    <div class="col-sm-4">
                                      <input class="form-control" name="supp_addr2" value="<?= $row['supp_addr2'] ?>"
                                             placeholder="<?= _("Direccion 2") ?>"/>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                    <div class="col-sm-4 offset-4">
                                      <input class="form-control" name="addr_zip" value="<?= $row['addr_zip'] ?>"
                                             placeholder="<?= _("C.Postal") ?>"/>
                                    </div>
                                    <div class="col-sm-4">
                                      <input class="form-control" name="addr_city" value="<?= $row['addr_city'] ?>"
                                             placeholder="<?= _("Ciudad") ?>"/>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                <label for="supp_cod" class="col-sm-4 control-label"><?= _("Telefonos") ?></label>
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
                                <label for="supp_cod" class="col-sm-4 control-label"><?= _("Internet") ?></label>
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

<!-- javascript in bottom view -->
