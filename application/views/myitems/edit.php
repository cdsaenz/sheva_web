

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
                               <h4><i class="fas fa-book mr-2"></i><?= $row['itm_name'] ?></h4>
                               <p class="small"><?=_("Codigo de Item:") . $row['itm_cod'] ?></p>
                           <?php else : ?>
                               <h4><i class="fas fa-book mr-2"></i><?= _("Nuevo Item") ?></h4>
                               <p class="small">Ingrese los datos para el nuevo registro</p>
                           <?php endif ?>
                      </div>
                      <div class="card-body cs-edit-body">

                          <!--- MULTITAB FORM !-->
                          <?= form_open("myitems/doedit","id='mainForm' class='form-horizontal md-form needs-validation' novalidate",$hidden) ?>
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
                                <?php if ($hidden["edit_mode"] == 'add' && $this->app->is_admin())  :  ?>
                                  <div class="form-group row mb-2">
                                   <label for="company_id" class="col-sm-4 control-label font-weight-bold"><?= _("Proveedor (Distribuidor)") ?></label>
                                   <div class="col-sm-8">
                                     <?= form_dropdown('company_id', $this->app->get_suppliers(true), $row['company_id'], "id='company_id' class='form-control' required"); ?>
                                         <div class="invalid-feedback">
                                             <p><?= _("Ingrese proveedor o distribuidor del item.") ?></p>
                                         </div>
                                   </div>
                                   </div>
                                <?php else: ?>
                                  <div class="form-group row mb-2">
                                   <label for="company_id" class="col-sm-4 control-label font-weight-bold text-danger"><?= _("Proveedor (Distribuidor)") ?></label>
                                   <div class="col-sm-8">
                                     <?= form_dropdown('company_id', $this->app->get_suppliers(true), $row['company_id'], "id='company_id' class='form-control' required disabled readonly"); ?>
                                         <div class="invalid-feedback">
                                             <p><?= _("Ingrese proveedor o distribuidor del item.") ?></p>
                                         </div>
                                   </div>
                                   </div>
                                <?php endif; ?>

                                <div class="form-group row mb-1">
                                <label for="itm_cod" class="col-sm-4 control-label"><?= _("Codigo") ?></label>
                                    <div class="col-sm-8">
                                      <input class="form-control" name="itm_cod" value="<?= $row['itm_cod'] ?>"
                                             placeholder="<?= _("Codigo de parte") ?>"/>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                <label for="itm_name" class="col-sm-4 control-label"><?= _("Nombre Item") ?></label>
                                    <div class="col-sm-8">
                                       <input class="form-control" name="itm_name" value="<?= $row['itm_name'] ?>"
                                             placeholder="<?= _("Nombre Item") ?>" required/>
                                       <div class="invalid-feedback">
                                           <p><?= _("Indicar nombre item.") ?></p>
                                       </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                <label for="itm_barcode" class="col-sm-4 control-label"><i class="fas fa-barcode mr-2"></i><?= _("Codigo de Barras") ?></label>
                                    <div class="col-sm-8">
                                       <input class="form-control" name="itm_barcode" value="<?= $row['itm_barcode'] ?>"
                                             placeholder="<?= _("Codigo de Barras") ?>" required/>
                                       <div class="invalid-feedback">
                                           <p><?= _("Indicar Codigo de Barras.") ?></p>
                                       </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                <label for="itm_is_disabled" class="col-sm-4 control-label font-weight-bold"><?= _("Suspendido") ?></label>
                                    <div class="col-sm-8">
                            			    <?= form_dropdown('itm_is_disabled', $this->items_model->get_yn_values(true), $row['itm_is_disabled'], "class='form-control' required"); ?>
                                          <div class="invalid-feedback">
                                              <p><?= _("Indicar si el item esta suspendido o no.") ?></p>
                                          </div>
                                    </div>
                                </div>

                               <div class="form-group row mb-2">
                                <label for="itm_type" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo o Familia") ?></label>
                                <div class="col-sm-8">
                        			    <?= form_dropdown('itm_type', $types, $row['itm_type'], "id='itm_type' class='form-control' required"); ?>
                                      <div class="invalid-feedback">
                                          <p><?= _("Ingrese tipo de item o producto.") ?></p>
                                      </div>
                                </div>
                                </div>


                               <div class="form-group row mb-1">
                                <label for="itm_package" class="col-sm-4 control-label"><?= _("Modulo/Bulto") ?></label>
                                    <div class="col-sm-2">
                                      <input class="form-control" name="itm_package" value="<?= $row['itm_package'] ?>"
                                             placeholder="<?= _("Ingrese Bulto (ej: 12)") ?>" />
                                    </div>
                               </div>

                                <div class="form-group row mb-1">
                                <label for="itm_price1" class="col-sm-4 control-label"><?= _("Precios") ?></label>
                                    <div class="col-sm-2">
                                      <input class="form-control" name="itm_price1" value="<?= $row['itm_price1'] ?>"
                                             placeholder="<?= _("Precio 1") ?>"/>
                                    </div>
                                    <div class="col-sm-2">
                                      <input class="form-control" name="itm_price2" value="<?= $row['itm_price2'] ?>"
                                             placeholder="<?= _("Precio 2") ?>"/>
                                    </div>
                                    <div class="col-sm-2">
                                      <input class="form-control" name="itm_price3" value="<?= $row['itm_price3'] ?>"
                                             placeholder="<?= _("Precio 3") ?>"/>
                                    </div>
                                    <div class="col-sm-2">
                                      <input class="form-control" name="itm_price4" value="<?= $row['itm_price4'] ?>"
                                             placeholder="<?= _("Precio 4") ?>"/>
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
