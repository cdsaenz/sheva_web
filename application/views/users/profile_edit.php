


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
                          <h2>
                              <i class="fas fa-user-check mr-2"></i><?= _("Modificar Mi Perfil") ?>
                          </h2>
                           <p><small><?=_("Por favor revise sus datos personales.") ?></small></p>
                      </div>
                      <div class="card-body cs-edit-body">
                          <?= form_open('users/doprofile', 'class="form-horizontal"', $hidden); ?>
                           <div class='form-group  row mb-1'>
                                <label for='nick' class='control-label col-sm-4'><?= _("ID o Alias") ?></label>
                                <div class='col-sm-8'>
                                    <input type="text" name="nick" value="<?= $row["nick"] ?>"  id='nick' class='form-control' placeholder= '<?= _("Ingrese su ID") ?>' required />
                                </div>
                            </div>
                            <div class='form-group  row mb-1'>
                                <label for='first_name' class='control-label col-sm-4'><?= _("Nombre") ?></label>
                                <div class='col-sm-8'>
                                    <input type="text" name="first_name" value="<?= $row["first_name"] ?>"  id='first_name' class='form-control' placeholder= '<?= _("Ingrese primer nombre") ?>' required />
                                </div>
                            </div>

                            <div class='form-group row mb-1'>
                                <label for='last_name' class='control-label col-sm-4'><?= _("Apellido") ?></label>
                                <div class='col-sm-8'>
                                    <input type="text" name="last_name" value="<?= $row["last_name"] ?>"  id='last_name' class='form-control' placeholder= '<?= _("Ingrese apellido") ?>' required />
                                </div>
                            </div>

                            <div class='form-group row mb-1'>
                                <label for='email' class='control-label col-sm-4'><?= _("Email") ?></label>
                                <div class='col-sm-8'>
                                    <input type="email" name="email" value="<?= $row["email"] ?>"  id='email' class='form-control' placeholder= '<?= _("Ingrese su direccion de email") ?>' required />
                                </div>
                            </div>

                            <div class="form-group row mb-2 mt-3">
                                <div class="col-sm-4 offset-4">
                                    <button type="submit" id="submitButton" class="btn btn-primary"><?= _("Confirmar") ?></button>
                                </div>
                            </div>

                         <?= form_close() ?>

                         <div class="row">
                             <div class="col-sm-4 offset-4 mt-2"><p><?= anchor("/users/pwd",_("Cambiar contrase&ntilde;a?"))  ?></p></div>
                         </div>
                      </div>
                    </div>
               </div>
           </div>
       </div>
