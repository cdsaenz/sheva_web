

       <div class="container-fluid">
           <div class="row">
               <div class="col-xs-12 col-lg-8 mx-auto">
                    <div class="card mt-3 shadow">
                      <div class="card-header cs-view-header">
                          <h4><i class="fas fa-user-check mr-2"></i><?= $title ?><br />
                            <small>
                                  <span class="badge badge-sm badge-info <?= $status ?>">
                                  <i class="far fa-compass mr-2"></i><?= $status ?></span>
                            </small>
                          </h4>

                           <div class="row">
                               <div class="col mx-auto text-center">
                                   <a href="<?= base_url("users/edit/$id") ?>" class="btn btn-dark float-center action-button3">
                                       <i class="fas fa-pen-square"></i>
                                       <div class="small"><?= _("Editar") ?></div>
                                   </a>
                                   <a href="<?= base_url("users") ?>" class="btn btn-dark float-center action-button3">
                                         <div class="fas fa-list"></div>
                                         <div class="small"><?= _("Listado") ?></div>
                                   </a>
                              </div>
                           </div>
                       </div>
                      <div class="card-body cs-view-body">
                          <?= form_open("#","class=form-horizontal",$hidden) ?>
                              <div class="form-group row mb-0">
                                  <label for="nick" class="col-sm-4 control-label"><?= _("Apodo/Alias") ?></label>
                                  <div class="col-sm-8">
                          		    <p class="form-control-static"><?= $row['nick'] ? $row['nick'] : "<i>No definido</i>"?></p>
                                  </div>
                              </div>

                              <div class="form-group row mb-0">
                                  <label for="first_name" class="col-sm-4 control-label"><?= _("Nombre") ?></label>
                                  <div class="col-sm-8">
                          		    <p class="form-control-static"><?= $row['first_name'] ?></p>
                                  </div>
                              </div>

                              <div class="form-group row mb-0">
                                  <label for="last_name" class="col-sm-4 control-label"><?= _("Apellido") ?></label>
                                  <div class="col-sm-8">
                          		    <p class="form-control-static"><?= $row['last_name'] ?></p>
                                  </div>
                              </div>

                              <div class="form-group row mb-0">
                                  <label for="email" class="col-sm-4 control-label"><?= _("Email") ?></label>
                                  <div class="col-sm-8">
                          		    <p class="form-control-static"><?= $row['email'] ?></p>
                                  </div>
                              </div>

                              <div class="form-group row mb-0">
                                   <label for="company_name" class="col-sm-4 control-label"><?= _("Telefono de contacto") ?></label>
                                   <div class="col-sm-8">
                                   <p class="form-control-static"><?= $row['phone'] ? $row['phone'] : "<i>No definido</i>"?></p>
                                   </div>
                               </div>


                              <div class="form-group row mb-0">
                                  <label for="msg_title" class="col-sm-4 control-label"><?= _("Administrador") ?></label>
                                  <div class="col-sm-8">
                           		    <p class="form-control-static">
                                        <span class="badge badge-danger p-2"><?=yesno($row['is_admin']) ?></span>
                                    </p>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="user_notes" class="col-sm-4 control-label"><?= _("Notas de Usuario") ?></label>
                                  <div class="col-sm-8">
                                      <textarea id="user_notes" name="user_notes" class="form-control" rows="3" readonly><?= $row['user_notes'] ?></textarea>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="admin_notes" class="col-sm-4 control-label"><?= _("Notas de Administrador") ?></label>
                                  <div class="col-sm-8">
                                      <textarea id="admin_notes" name="admin_notes" class="form-control" rows="3" readonly><?= $row['admin_notes'] ?></textarea>
                                  </div>
                              </div>
                          <?= form_close() ?>

                      </div>
                    </div>
               </div>
           </div>
       </div>
