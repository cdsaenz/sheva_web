
       <div class="container-fluid">
           <div class="row">
               <div class="col-sm-12 col-md-10 col-lg-8 mx-auto">
                       <div class="card mt-2 shadow">
                         <div class="card-header cs-view-header">
                             <div class="row">
                               <div class="col-xs-12 col-md-2 text-center mx-auto my-auto">
                                 <img src="<?=$logo ?>" class="img-thumbnail shadow img-fluid" style="width: 100px; height: 100px" alt="Logo" />
                                 <div class="caption center-block small p-1 mt-1 mb-2">Logo</div>
                               </div>
                               <div class="col-xs-12 col-md-10 text-center mx-auto my-auto">
                                 <h4><i class="fas fa-shopping-cart mr-2"></i><?= $row['supp_name'] ?></h4>
                                 <p class="small"><?=_("Codigo de Distribuidor:") . $row['supp_cod'] ?></p>
                               </div>
                             </div>

                             <div class="row">
                               <div class="col mx-auto text-center">
                                    <a href="<?= base_url("suppliers/edit/$id") ?>" class="btn btn-dark align-middle col-2">
                                          <div class="fas fa-pen-square"></div>
                                          <div class="small"><?= _("Editar") ?></div>
                                    </a>
                                    <a href="<?= base_url("items/priceup/$id") ?>" class="btn btn-dark align-middle col-2">
                                          <div class="fas fa-dollar-sign"></div>
                                          <div class="small"><?= _("Precios") ?></div>
                                    </a>
                                    <a href="<?= base_url("suppliers") ?>" class="btn btn-dark align-middle col-2">
                                          <div class="fas fa-list"></div>
                                          <div class="small"><?= _("Listado") ?></div>
                                    </a>
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
                               <?php if ($hidden["edit_mode"] == 'add') :  ?>
                                  <div class="form-group row mb-1">
                                  <label for="supp_cod" class="col-sm-4 control-label"><?= _("Codigo") ?></label>
                                      <div class="col-sm-8">
                                           <p class="form-control-static"><?= $row['supp_cod'] ?></p>
                                      </div>
                                  </div>
                               <?php endif ?>

                                <div class="form-group row mb-1">
                                <label for="supp_name" class="col-sm-4 control-label"><?= _("Nombre o Razon Social") ?></label>
                                    <div class="col-sm-8">
                                           <p class="form-control-static"><?= $row['supp_name'] ?></p>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                <label for="supp_is_disabled" class="col-sm-4 control-label font-weight-bold"><?= _("Suspendido") ?></label>
                                <div class="col-sm-8">
                                           <p class="form-control-static"><?= yesno($row['supp_is_disabled']) ?></p>
                                </div>
                                </div>

                               <div class="form-group row mb-2">
                                <label for="supp_type" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo o Familia") ?></label>
                                <div class="col-sm-8">
                                           <p class="form-control-static"><?= $row['supp_type'] ?></p>
                                </div>
                                </div>


                            </div>

                            <!--- ADDRESS TAB !-->
                            <div class="tab-pane fade mt-3 ml-2" id="addr" role="tabpanel" aria-labelledby="addr-tab">
                                <div class="form-group row mb-2">
                                 <label class="col-sm-4 control-label font-weight-bold"><?= _("Direccion") ?></label>
                                   <div class="col-sm-3">
                                       <p class="form-control-static"><?= $row['supp_addr1'] ?></p>
                                   </div>
                                   <div class="col-sm-3">
                                       <p class="form-control-static"><?= $row['supp_addr2'] ?></p>
                                   </div>
                                   <div class="col-sm-2">
                                       <p class="form-control-static"><?= $row['addr_zip'] ?></p>
                                   </div>
                                </div>

                                <div class="form-group row mb-2">
                                 <label class="col-sm-4 control-label font-weight-bold"><?= _("Telefonos") ?></label>
                                   <div class="col-sm-4">
                                       <p class="form-control-static"><?= $row['phone_nbr1'] ?></p>
                                   </div>
                                   <div class="col-sm-4">
                                       <p class="form-control-static"><?= $row['phone_nbr2'] ?></p>
                                   </div>
                                </div>

                                <div class="form-group row mb-2">
                                 <label class="col-sm-4 control-label font-weight-bold"><?= _("Email y Web Site") ?></label>
                                   <div class="col-sm-4">
                                       <p class="form-control-static"><?= $row['email_addr'] ?></p>
                                   </div>
                                   <div class="col-sm-4">
                                       <p class="form-control-static"><?= $row['www'] ?></p>
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
