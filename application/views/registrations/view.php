
       <div class="container-fluid">
           <div class="row">
               <div class="col-sm-12 col-md-8 mx-auto">
                    <div class="card mt-2 shadow">
                      <div class="card-header cs-view-header">
                          <h4><i class="fas fa-user-check mr-2"></i>Registracion para: <?= $row['cust_name'] ?></h4>
                          <p class="small">Datos de la solicitud</p>

                          <div class="row">
                            <div class="col mx-auto text-center">
                                 <a href="<?= base_url("registrations/eval/$id") ?>" class="btn btn-dark align-middle col-2">
                                       <div class="fas fa-pen-square"></div>
                                       <div class="small"><?= _("Validar") ?></div>
                                 </a>
                                 <a href="<?= base_url("registrations") ?>" class="btn btn-dark align-middle col-2">
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
                              <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">
                              <i class="fas fa-sticky-note"></i></a>
                            </li>
                          </ul>
                          <!--- CONTENT !-->
                          <div class="tab-content" id="topTabContent">
                            <!--- MAIN TAB !-->
                            <div class="tab-pane fade show active mt-3 ml-2" id="main" role="tabpanel" aria-labelledby="main-tab">
                              <div class="form-group row mb-1">
                              <label for="reg_status" class="col-sm-4 control-label"><?= _("Status solicitud") ?></label>
                                  <div class="col-sm-8">
                                         <p class="form-control-static <?= $status_class ?>"><?= $row['reg_status'] ?></p>
                                  </div>
                              </div>

                              <div class="form-group row mb-1">
                              <label for="reg_status" class="col-sm-4 control-label"><?= _("Tipo solicitud") ?></label>
                                  <div class="col-sm-8">
                                         <p class="form-control-static badge badge-primary p-1"><?= $this->app->get_code_label("reg_type",$row['reg_type']) ?></p>
                                  </div>
                              </div>

                              <div class="form-group row mb-1">
                              <label for="cust_name" class="col-sm-4 control-label"><?= _("Fecha solicitud") ?></label>
                                  <div class="col-sm-8">
                                         <p class="form-control-static"><?= AnsiDateToDMY($row['posted_on']) ?></p>
                                  </div>
                              </div>

                               <div class="form-group row mb-1">
                               <label for="cust_name" class="col-sm-4 control-label"><?= _("Distribuidor/Token") ?></label>
                                   <div class="col-sm-8">
                                          <p class="form-control-static"><?= $row['supp_name'] ?>/<?= $row['supp_token'] ?></p>
                                   </div>
                               </div>

                                <div class="form-group row mb-1">
                                <label for="cust_name" class="col-sm-4 control-label"><?= _("Cliente") ?></label>
                                    <div class="col-sm-8">
                                           <p class="form-control-static"><?= $row['cust_name'] ?></p>
                                    </div>
                                </div>

                               <div class="form-group row mb-2">
                                <label for="usr_email_addr" class="col-sm-4 control-label font-weight-bold"><?= _("Email usuario") ?></label>
                                <div class="col-sm-8">
                                           <p class="form-control-static"><?= $row['usr_email_addr'] ?></p>
                                </div>
                                </div>

                                <div class="form-group row mb-2">
                                 <label for="tax_id" class="col-sm-4 control-label font-weight-bold"><?= _("CUIT") ?></label>
                                   <div class="col-sm-8">
                                       <p class="form-control-static"><?= $row['tax_id'] ?></p>
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
