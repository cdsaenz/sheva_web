

       <div class="container-fluid">

             <div class="row">
                <div class="col-xs-12 col-lg-12 text-center loading" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-sm-6 col-lg-6 alert mx-auto text-center alert-danger m-5 p-2" id="errors" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-xs-12 col-lg-8 mx-auto">
                    <div class="card mt-3 shadow">
                      <div class="card-header cs-edit-header">
                           <h4><i class="fas fa-book mr-2 mb-2"></i><?= $title ?></h4>
                           <?php if ($hidden["edit_mode"] == 'edit') :  ?>
                               <p class="small"><?=_("Modifique datos del usuario") ?></p>
                           <?php else : ?>
                               <p class="small"><?=_("Ingrese datos del nuevo usuario") ?></p>
                           <?php endif ?>
                          <span class="badge badge-sm badge-info">
                                 <i class="far fa-compass mr-2"></i><?= $row['is_active'] == "Y" ? _("Activo") : _("Inactivo") ?>
                          </span>                          
                      </div>
                      <div class="card-body cs-edit-body">

                            <?= form_open("users/doedit","class='form-horizontal needs-validation' novalidate",$hidden) ?>

                              <div class="form-group row mb-2">
                               <label for="user_company_type" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo de Usuario/Empresa") ?></label>
                               <div class="col-sm-8">
                                 <?= form_dropdown('supp_type', $this->users_model->get_select_values("user_company_type",true), $row['user_company_type'], "id='user_company_type' class='form-control' required"); ?>
                                     <div class="invalid-feedback">
                                         <p><?= _("Ingrese tipo de usuario") ?></p>
                                     </div>
                               </div>
                               </div>

                              <div class="form-group row mb-1">
                              <label for="supp_name" class="col-sm-4 control-label"><?= _("Seleccione Empresa") ?></label>
                                  <div class="col-sm-8">
                                    <input class="form-control company_input" id="supp_name" data-id="user_company_id"
                                           value=""
                                           placeholder="<?= _("Tipee dos letras para buscar nombre empresa") ?>" required/>
                                    <div class="invalid-feedback">
                                        <p><?= _("Debe seleccionar una empresa.") ?></p>
                                    </div>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="nick" class="col-sm-4 control-label font-weight-bold"><?= _("Apodo/Alias") ?></label>
                                  <div class="col-sm-8">
                                    <input name="nick" class="form-control text-uppercase" id="nick" placeholder="<?= _("Ingrese Alias o apodo ej: JPEREZ10") ?>" value="<?= $row['nick'] ?>" required>
                                    <div class="invalid-feedback">
                                        <p><?= _("Debe ingresar un alias unico.") ?></p>
                                    </div>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                 <label for="first_name" class="col-sm-4 control-label"><?= _("Nombre") ?></label>
                                 <div class="col-sm-8">
                                   <input name="first_name" class="form-control" id="first_name" placeholder="<?= _("Ingrese primer nombre") ?>" value="<?= $row['first_name'] ?>" required>
                                    <div class="invalid-feedback">
                                        <p><?= _("Debe ingresar primer nombre.") ?></p>
                                    </div>
                                 </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="last_name" class="col-sm-4 control-label"><?= _("Apellido") ?></label>
                                  <div class="col-sm-8">
                                    <input name="last_name" class="form-control" id="last_name" placeholder="<?= _("Ingrese apellido") ?>" value="<?= $row['last_name'] ?>" required>
                                    <div class="invalid-feedback">
                                        <p><?= _("Debe ingresar apellido del usuario.") ?></p>
                                    </div>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="email" class="col-sm-4 control-label font-weight-bold"><?= _("Email") ?></label>
                                  <div class="col-sm-8">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="<?= _("Ingrese Email valido") ?>" value="<?= $row['email'] ?>" required>
                                    <div class="invalid-feedback">
                                        <p><?= _("Debe ingresar un email para el usuario.") ?></p>
                                    </div>
                                  </div>
                              </div>

                             <div class="form-group row mb-2">
                                  <label for="phone" class="col-sm-4 control-label font-weight-bold"><?= _("Telefono usuario") ?></label>
                                  <div class="col-sm-8">
                                    <input name="phone" class="form-control" id="phone" placeholder="<?= _("Ingrese Telefono de contacto") ?>" value="<?= $row['phone'] ?>">
                                    <div class="invalid-feedback">
                                        <p><?= _("Debe ingresar un telefono de contacto.") ?></p>
                                    </div>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="is_admin" class="col-sm-4 control-label"><?= _("Admin") ?></label>
                                  <div class="col-sm-8">
                                    <?= form_dropdown('is_admin', $this->users_model->get_yn_values(true), $row['is_admin'], "class='form-control' required"); ?>
                                    <div class="invalid-feedback">
                                        <p><?= _("Indique si el usuario es administrador") ?></p>
                                    </div>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="is_genera" class="col-sm-4 control-label"><?= _("Creacion de pedidos") ?></label>
                                  <div class="col-sm-8">
                                    <?= form_dropdown('is_genera', $this->users_model->get_yn_values(true), $row['is_genera'], "class='form-control' required"); ?>
                                    <div class="invalid-feedback">
                                        <p><?= _("Indique si el usuario es generador de pedidos") ?></p>
                                    </div>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="is_supp" class="col-sm-4 control-label"><?= _("Distribuidor") ?></label>
                                  <div class="col-sm-8">
                                    <?= form_dropdown('is_supp', $this->users_model->get_yn_values(true), $row['is_supp'], "class='form-control' required"); ?>
                                    <div class="invalid-feedback">
                                        <p><?= _("Indique si el usuario es distribuidor") ?></p>
                                    </div>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="admin_notes" class="col-sm-4 control-label"><?= _("Notas de Administrador") ?></label>
                                  <div class="col-sm-8 mt-2">
                                      <textarea id="admin_notes" name="admin_notes" class="form-control" rows="3"><?= $row['admin_notes'] ?></textarea>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                    <div class="col-sm-4 offset-4 mt-2">
                                        <button type="submit" id="submitButton" class="btn btn-primary"><?= _("Guardar") ?></button>
                                    </div>
                              </div>

                              <?= form_close() ?>

                      </div>
                    </div>
               </div>
           </div>
       </div>

<!-- JavaScript in bottom view -->
