

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
                          <h4>
                              <i class="fas fa-file-invoice mr-2"></i><?= $title ?>
                          </h4>
                          <p>
                             <?php if ($hidden["edit_mode"] == 'edit') :  ?>
                                 <p class="small"><?=_("Modificar Archivo") ?></p>
                             <?php else : ?>
                                 <p class="small"><?=_("Agregar Archivo") ?></p>
                             <?php endif ?>
                           </p>
                           <p><?= _("Para: ") ?><?= $related ?></p>
                           <?php if ($hidden["edit_mode"] == 'edit') :  ?>
                             <div class="row mx-auto">
                                 <div class="btn-group text-center align-middle rounded-top bg-dark btn-block" role="group" aria-label="<?= _("Actions") ?>">
                                        <a href="<?= base_url("media/delete/$id") ?>" class="btn btn-danger align-middle col-xs-12 col-md-2">
                                              <div class="fas fa-trash"></div>
                                              <div class="small"><?= _("Eliminar") ?></div>
                                        </a>
                                 </div>
                             </div>
                           <?php endif ?>
                      </div>
                      <div class="card-body cs-edit-body">
                          <?php if ($url) : ?>
                            <div class="card d-block mb-4 h-100  mx-auto text-center">
                                <div class="card-body cs-view-body">
                                    <?php if (is_image($row['media_src'])): ?>
                                        <img src="<?= default_img($row); ?>" alt="picture" class="img-fluid" style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <a href="<?= default_img($row);  ?>" target="_blank">Link a archivo adjunto</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                          <?php endif ?>

                         <!--- MULTITAB FORM !-->
                          <?= form_open_multipart('media/doedit', "class='form-horizontal needs-validation' novalidate", $hidden); ?>
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
                                <div class="form-group row mb-2">
                                <label for="media_name" class="col-sm-4 control-label font-weight-bold"><?= _("Descripcion Archivo") ?></label>
                                <div class="col-sm-8">
                                  <input name="media_name" class="form-control" id="media_name" placeholder="<?= _("Title ex: Marantz 2285B Back picture") ?>" value="<?= $row['media_name'] ?>" required>
                                  <div class="invalid-feedback">
                                      <p><?= _("Debe ingresar una descripcion") ?></p>
                                  </div>
                                </div>
                                </div>

                                <div class="form-group row mb-2">
                                <label for="media_origin" class="col-sm-4 control-label font-weight-bold"><?= _("Sitio Origen") ?></label>
                                <div class="col-sm-8">
                                  <input name="media_origin" class="form-control" id="media_origin" placeholder="<?= _("ej: Google, Wikipedia, etc") ?>" value="<?= $row['media_origin'] ?>" required>
                                  <div class="invalid-feedback">
                                      <p><?= _("Ingrese origen del archivo.") ?></p>
                                  </div>
                                </div>
                                </div>

                                <div class="form-group row mb-2">
                                <label for="media_type" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo Archivo") ?></label>
                                <div class="col-sm-8">
                                <?= form_dropdown('media_type', $this->media_model->get_select_labels("media_type",false), $row['media_type'], "class='form-control' required"); ?>
                                      <div class="invalid-feedback">
                                          <p><?= _("Indique el tipo de medios que esta ingresando.") ?></p>
                                      </div>
                                </div>
                                </div>

                                <div class="form-group row mb-2">
                                <label for="src_type" class="col-sm-4 control-label font-weight-bold"><?= _("Archivo o Vinculo URL (Link)") ?></label>
                                <div class="col-sm-8">
                                <?= form_dropdown('src_type', $this->media_model->get_select_labels("src_type",false), $row['src_type'], "id='src_type' class='form-control' required"); ?>
                                      <div class="invalid-feedback">
                                          <p><?= _("Archivo o Link?") ?></p>
                                      </div>
                                </div>
                                </div>

                                <div class="form-group row mb-2">
                                <label for="is_main" class="col-sm-4 control-label font-weight-bold"><?= _("Archivo principal de este tipo") ?></label>
                                <div class="col-sm-8">
                                <?= form_dropdown('is_main', $this->media_model->get_yn_values(false), $row['is_main'], "id='is_main' class='form-control' required"); ?>
                                      <div class="invalid-feedback">
                                          <p><?= _("Archivo principal de este tipo (FOTO, PDF etc)") ?></p>
                                      </div>
                                </div>
                                </div>

                                <?php if ($hidden["edit_mode"] == 'edit') :  ?>

                                   <div class="form-group row mb-2">
                                        <label for="media_src" class="col-sm-4 control-label"><?= _("Link o Archivo") ?></label>
                                        <div class="col-sm-8">
                                            <input name="media_src" class="form-control" id="media_src" placeholder="<?= _("ex: http://www.company.com/something") ?>" value="<?= $row['media_src'] ?>" required>
                                            <div class="invalid-feedback">
                                                <p><?= _("LINK (url) o Nombre archivo ya subido") ?></p>
                                            </div>
                                        </div>
                                    </div>

                                <?php else :  ?>
                                  <div id="link_section" style="display: none">
                                    <div class="form-group row mb-2">
                                      <label for="link_media_src" class="col-sm-4 control-label font-weight-bold"><?= _("Link") ?></label>
                                      <div class="col-sm-8">
                                        <input name="link_media_src" class="form-control" id="link_media_src" placeholder="<?= _("ex: http://www.company.com/something") ?>" value="<?= $row['media_src'] ?>" >
                                        <div class="invalid-feedback">
                                            <p><?= _("URL para este link") ?></p>
                                        </div>
                                      </div>
                                    </div>
                                 </div>
                                 <div id="upload_section" style="display: visible">
                                   <div class="form-group row mb-2">
                                      <div class="col-12 col-sm-4">
                                          <label for="file_name"><?= _("Archivo a subir") ?></label>
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
                               <?php endif ?>
                            </div>



                            <!--- NOTES TAB !-->
                            <div class="tab-pane fade mt-3 ml-2" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                <div class="form-group row mb-2">
                                  <label for="notes" class="col-sm-4 control-label"><?= _("Notas") ?></label>
                                  <div class="col-sm-8">
                                      <textarea id="notes" name="notes" class="form-control" rows="3"><?= $row['notes'] ?></textarea>
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

<!-- JavaScript in bottom view s-->
