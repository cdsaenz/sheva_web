

       <div class="container-fluid">

             <div class="row">
                <div class="col-xs-12 col-lg-12 text-center loading" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-sm-6 col-lg-6 alert mx-auto text-center alert-danger m-5 p-2" id="errors" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-xs-12 col-lg-8 mx-auto">
                    <div class="card mt-3">
                      <div class="card-header cs-edit-header">
                         <h4><i class="fas fa-tools mr-2"></i><?= $title ?></h4>                          
                         <?php if ($hidden["edit_mode"] == 'edit') :  ?>
                             <p class="small"><?=_("Modifique datos del codigo de tabla") ?></p>
                         <?php else : ?>
                             <p class="small"><?=_("Ingrese datos del codigo de tabla") ?></p>
                         <?php endif ?>
                      </div>
                      <div class="card-body cs-edit-body">
                            <?= form_open("codes/doedit","class='form-horizontal needs-validation' novalidate",$hidden) ?>
                             <?php if ($hidden["edit_mode"] == 'edit') :  ?>
                                 <div class="form-group row mb-1">
                                      <label for="code_field" class="col-sm-4 control-label"><?= _("Tipo de tabla") ?></label>
                                      <div class="col-sm-8">
                              		    <p class="form-control-static"><?= $row['code_field'] ?></p>
                                      </div>
                                  </div>

                                  <div class="form-group row mb-1">
                                      <label for="code_value" class="col-sm-4 control-label"><?= _("Codigo de tabla") ?></label>
                                      <div class="col-sm-8">
                              		    <p class="form-control-static"><?= $row['code_value'] ?></p>
                                      </div>
                                  </div>
                             <?php else : ?>
                               <div class="form-group row mb-2">
                                  <label for="code_field" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo de Tabla") ?></label>
                                  <div class="col-sm-8">
                         			    <?= form_dropdown('code_field', $this->codes_model->get_field_types(), $row['code_field'], "class='form-control' required"); ?>
                                        <div class="invalid-feedback">
                                            <p><?= _("Debe seleccionar un codigo de campo") ?></p>
                                        </div>
                                  </div>
                                </div>

                                <div class="form-group row mb-1">
                                <label for="code_value" class="col-sm-4 control-label"><?= _("Ingrese un valor para el codigo") ?></label>
                                    <div class="col-sm-8">
                                      <input class="form-control" name="code_value" value="<?= $row['code_value'] ?>"
                                             placeholder="<?= _("Codigo de la tabla a ser mostrado") ?>" required/>
                                      <div class="invalid-feedback">
                                          <p><?= _("Debe ingresar codigo tabla.") ?></p>
                                      </div>
                                    </div>
                                </div>
                             <?php endif ?>

                              <div class="form-group row mb-1">
                              <label for="code_number" class="col-sm-4 control-label"><?= _("Orden en lista (Opcional)") ?></label>
                                  <div class="col-sm-8">
                                    <input type="number" class="form-control" name="code_number" value="<?= $row['code_number'] ?>"
                                           placeholder="<?= _("Orden dentro del combo de usuario") ?>" min="0" max="999"/>
                                  </div>
                              </div>

                              <div class="form-group row mb-1">
                              <label for="code_text" class="col-sm-4 control-label"><?= _("Texto asociado") ?></label>
                                  <div class="col-sm-8">
                                    <input class="form-control" name="code_text" value="<?= $row['code_text'] ?>"
                                           placeholder="<?= _("Texto opcional asociado (Max 20)") ?>"/>
                                  </div>
                              </div>


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
