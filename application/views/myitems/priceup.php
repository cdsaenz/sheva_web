
       <div class="container-fluid">
           <div class="row">
               <div class="col-xs-12 col-md-10 col-lg-8 col-xl-8 mx-auto">
                    <div class="card mt-2 shadow">
                      <div class="card-header cs-view-header">
                         <div class="row">
                           <div class="col-xs-12 col-md-10 text-center mx-auto">
                                <div class="row mt-2">
                                   <div class="col">
                                     <h5><i class="fas fa-user-check mr-2"></i>Cambio de Precios Masivo
                                       <p class="small"><?= _("Distribuidor: ") ?><b><?= $cid ?></b></p>
                                     </h5>
                                     <h5 class="font-weight-bold"><?= $supp_name ?></h5>
                                   </div>
                                </div>

                           </div>
                         </div>

                       </div>
                      <div class="card-body cs-view-body">

                          <!--- TAB CAPTIONS !-->
                          <ul class="nav nav-tabs cs-view-tabs" id="topTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true">
                              <?= _("Principal") ?></a>
                            </li>
                          </ul>

                          <!--- CONTENT !-->
                          <div class="tab-content" id="topTabContent">
                            <!--- MAIN TAB !-->
                            <div class="tab-pane fade show active mt-3 ml-2" id="main" role="tabpanel" aria-labelledby="main-tab">
                              <?= form_open("myitems/dopriceup","id='mainForm' class='form-horizontal md-form needs-validation' novalidate",$hidden) ?>
                              <div class="form-group row mb-2">
                                <label for="pct_chg" class="col-sm-4 control-label"><?= _("% de Cambio (- para disminuir)") ?></label>
                                    <div class="col-sm-8">
                                       <input type="number" class="form-control" name="pct_chg" value=""
                                             placeholder="<?= _("% de cambio de precio") ?>" required/>
                                       <div class="invalid-feedback">
                                           <p><?= _("Indicar % de cambio precio.") ?></p>
                                       </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                 <label for="itm_type" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo o Familia") ?></label>
                                 <div class="col-sm-8">
                                   <?= form_dropdown('itm_type', $types, "", "id='itm_type' class='form-control'"); ?>
                                 </div>
                                 </div>

                                  <div class="form-group row mb-2">
                                   <label for="lista_id" class="col-sm-4 control-label font-weight-bold"><?= _("Lista de Precios") ?></label>
                                   <div class="col-sm-8">
                                     <?= form_dropdown('lista_id', ["1"=>"1","2"=>"2","3"=>"3","4"=>"4"],"", "id='lista_id' class='form-control' required"); ?>
                                     <div class="invalid-feedback">
                                         <p><?= _("Ingrese lista a cambiar.") ?></p>
                                     </div>
                                   </div>
                                   </div>
                              </div>
                            </div>

                            <!--- SAVE BUTTON OUT OF THE TABS !-->
                            <div class="form-group row mb-2 mt-3">
                                  <div class="col-sm-4 offset-4">
                                      <button type="submit" id="submitButton" class="btn btn-danger"><?= _("Procesar") ?></button>
                                  </div>
                            </div>

                            <?= form_close() ?>
                            </div>
                          </div>
                      </div>
                    </div>
               </div>
           </div>
        </div>
