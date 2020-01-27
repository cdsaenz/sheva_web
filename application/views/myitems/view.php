
       <div class="container-fluid">
           <div class="row">
               <div class="col-xs-12 col-md-10 col-lg-8 col-xl-8 mx-auto">
                    <div class="card mt-2 shadow">
                      <div class="card-header cs-view-header">
                         <div class="row">
                           <div class="col-xs-12 col-md-2 text-center mx-auto my-auto">
                              <img src="<?= default_img($row['main_pic'],'items') ?>"
                                   class="img-thumbnail shadow img-fluid" style="width: 100px; height: 100px" alt="Imagen" />
                           </div>
                           <div class="col-xs-12 col-md-10 text-center mx-auto">
                                <div class="row mt-2">
                                   <div class="col">
                                     <h5><i class="fas fa-user-check mr-2"></i><?= $row['itm_name'] ?>
                                       <p class="small"><?= _("Codigo de Item: ") ?><b><?= $row['itm_cod'] ?></b></p>
                                     </h5>
                                     <h5 class="font-weight-bold"><?= $row['supp_name'] ?></h5>
                                   </div>
                                </div>

                                <div class="row">
                                    <div class="col mx-auto text-center">
                                      <a href="<?= base_url("myitems/edit/$id") ?>" class="btn btn-dark float-center action-button3">
                                          <i class="fas fa-pen-square"></i>
                                          <div class="small"><?= _("Editar") ?></div>
                                      </a>
                                      <a href="<?= base_url("myitems") ?>" class="btn btn-dark float-center action-button3">
                                            <div class="fas fa-list"></div>
                                            <div class="small"><?= _("Listado") ?></div>
                                      </a>
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
                              <?= _("Item") ?></a>
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
                               <?php if ($hidden["edit_mode"] == 'add') :  ?>
                                  <div class="form-group row mb-1">
                                  <label for="itm_cod" class="col-sm-4 control-label"><?= _("Codigo") ?></label>
                                      <div class="col-sm-8">
                                           <p class="form-control-static"><?= $row['itm_cod'] ?></p>
                                      </div>
                                  </div>
                               <?php endif ?>

                                <div class="form-group row mb-1">
                                <label for="itm_name" class="col-sm-4 control-label"><?= _("Nombre") ?></label>
                                    <div class="col-sm-8">
                                           <p class="form-control-static"><?= $row['itm_name'] ?></p>
                                    </div>
                                </div>

                                <div class="form-group row mb-1">
                                <label for="itm_barcode" class="col-sm-4 control-label"><i class="fas fa-barcode"></i>
                                      <?= _("Codigo de Barras") ?></label>
                                    <div class="col-sm-8">
                                           <p class="form-control-static"><?= $row['itm_barcode'] ?></p>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                <label for="itm_is_disabled" class="col-sm-4 control-label font-weight-bold"><?= _("Suspendido") ?></label>
                                <div class="col-sm-8">
                                           <p class="form-control-static"><?= yesno($row['itm_is_disabled']) ?></p>
                                </div>
                                </div>

                               <div class="form-group row mb-2">
                                <label for="itm_type" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo o Familia") ?></label>
                                <div class="col-sm-8">
                                           <p class="form-control-static"><?= $row['type_name'] ?></p>
                                </div>
                                </div>

                                 <div class="form-group row mb-2">
                                  <label for="itm_package" class="col-sm-4 control-label"><?= _("Modulo/Bulto") ?></label>
                                  <div class="col-sm-8">
                                       <p class="form-control-static"><?= $row['itm_package'] ?></p>
                                 </div>
                                 </div>

                                <div class="form-group row mb-1">
                                    <label for="price_amt" class="col-sm-4 control-label"><?= _("Precios por lista") ?></label>
                                    <div class="col-sm-2">
                                          <p class="form-control-static"><?= $row['itm_price1'] ?></p>
                                    </div>
                                    <div class="col-sm-2">
                                          <p class="form-control-static"><?= $row['itm_price2'] ?></p>
                                    </div>
                                    <div class="col-sm-2">
                                          <p class="form-control-static"><?= $row['itm_price3'] ?></p>
                                    </div>
                                    <div class="col-sm-2">
                                          <p class="form-control-static"><?= $row['itm_price4'] ?></p>
                                    </div>
                                </div>
                            </div>


                           <!--- NOTES TAB !-->
                            <div class="tab-pane fade mt-3 ml-2" id="notes" role="tabpanel" aria-labelledby="notes-tab">

                                <label for="notes" class="col-sm-4 control-label"><?= _("Notas") ?></label>
                                <div class="form-group row mb-2">
                                  <div class="col-sm-12">
                                      <textarea id="notes" name="notes" class="form-control" readonly rows="5"><?= $row['notes'] ?></textarea>
                                  </div>
                                </div>

                            </div>
                          </div>

                      </div>
                    </div>
               </div>
           </div>
        </div>
