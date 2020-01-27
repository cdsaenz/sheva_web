

       <div class="container-fluid">
           <div class="row">
               <div class="col-xs-12 col-lg-8 mx-auto">
                    <div class="card mt-3">
                      <div class="card-header cs-view-header">

                          <h4><i class="fas fa-file-invoice mr-2"></i><?= $title ?></h4>
                          <p class="small"><?= _("Informacion Archivo") ?></p>
                          <p><?= _("Para: ") ?><?= $related ?></p>

                          <div class="row">
                              <div class="col mx-auto text-center">
                                      <a href="<?= base_url("media/edit/$id") ?>" class="btn btn-dark align-middle col-2">
                                            <div class="fas fa-pen-square"></div>
                                            <div class="small"><?= _("Editar") ?></div>
                                      </a>
                                      <a href="<?= base_url("media") ?>" class="btn btn-dark align-middle col-2">
                                            <div class="fas fa-list"></div>
                                            <div class="small"><?= _("Listado") ?></div>
                                      </a>
                               </div>
                           </div>
                      </div>
                      <div class="card-body cs-view-body">
                          <div class="card d-block mb-4 h-100 mx-auto text-center">
                              <div class="card-body cs-view-body">
                                  <?php if (is_image($row['media_src'])): ?>
                                      <img src="<?= default_img($row); ?>" alt="picture" class="img-fluid img-thumbnail" style="height: 200px; object-fit: cover;">
                                  <?php else: ?>
                                      <a href="<?= default_img($row);  ?>" target="_blank">Link a archivo adjunto</a>
                                  <?php endif; ?>
                              </div>
                          </div>

                          <?= form_open("#","class=form-horizontal",$hidden) ?>
                          <div class="form-group row mb-0">
                              <label for="media_type" class="col-sm-4 control-label"><?= _("Owner") ?></label>
                              <div class="col-sm-8">
                      		    <p class="form-control-static">(<?= $row['media_company_id'] ?>) <i><?= $row['media_company_name'] ?></i></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="media_type" class="col-sm-4 control-label"><?= _("Tipo de Medios/Tipo de Origen") ?></label>
                              <div class="col-sm-8">
                      		    <p class="form-control-static"><?= $row['media_type'] ?> <i><?= $row['src_type'] ?></i></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="media_name" class="col-sm-4 control-label"><?= _("Descripcion o Nombre") ?></label>
                              <div class="col-sm-8">
                      		    <p class="form-control-static"><?= $row['media_name'] ?></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="media_src" class="col-sm-4 control-label"><?= _("Nombre archivo") ?></label>
                              <div class="col-sm-8">
                          		    <p class="form-control-static"><?= $row['media_src'] ?></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="src_name" class="col-sm-4 control-label"><?= _("Nombre original") ?></label>
                              <div class="col-sm-8">
                                  <p class="form-control-static"><?= $row['src_name'] ?></p>
                              </div>
                          </div>

                          <div class="form-group row mb-0">
                              <label for="media_src" class="col-sm-4 control-label"><?= _("Principal") ?></label>
                              <div class="col-sm-8">
                                  <p class="form-control-static"><?= yesno($row['is_main']) ?></p>
                              </div>
                          </div>


                          <?= form_close() ?>


                      </div>
                    </div>
               </div>
           </div>
       </div>
