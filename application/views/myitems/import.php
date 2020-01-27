

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
                      <div class="card-header cs-view-header">
                          <h4><i class="fas fa-cogs mr-2"></i><?= $title ?></h4>
                          <p class="small"><?= _("Incorporar items a la base de datos de un archivo delimitado") ?></p>
                      </div>
                      <div class="card-body cs-view-body">

                         <!--- MULTITAB FORM !-->
                          <?= form_open_multipart('myitems/doimport', "class='form-horizontal needs-validation' novalidate", $hidden); ?>
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
                                 <div class="form-group row mb-1">
                                 <label for="itm_name" class="col-sm-3 control-label"><?= _("Instrucciones") ?></label>
                                    <div class="col-sm-9">
                                           <p class="form-control-static blockquote shadow p-3">
                                                <b>Formato esperado:</b><br />
                                                <small>Codigo;Barcode;Tipo Articulo;Descripcion;Precio1;Precio2;Precio3;Precio4;Bulto</small>
                                           </p>

                                           <p class="form-control-static blockquote shadow p-3">
                                                <b>Notas</b><br />
                                                - Separar con punto y coma cada campo<br />
                                                - Bulto es opcional<br />
                                                - La primera linea se considera de titulos, se saltea<br />
                                                - El Tipo de articulo es numerico y debe existir en la DB.<br />
                                           </p>
                                    </div>
                                 </div>

                                 <?php if ($this->app->is_admin())  :  ?>
                                   <div class="form-group row mb-2">
                                    <label for="company_id" class="col-sm-3 control-label font-weight-bold"><?= _("Proveedor (Distribuidor)") ?></label>
                                    <div class="col-sm-9">
                                      <?= form_dropdown('company_id', $this->app->get_suppliers(true), $company_id, "id='company_id' class='form-control' required"); ?>
                                          <div class="invalid-feedback">
                                              <p><?= _("Ingrese proveedor o distribuidor del item.") ?></p>
                                          </div>
                                    </div>
                                    </div>
                                 <?php else: ?>
                                   <div class="form-group row mb-2">
                                    <label for="company_id" class="col-sm-4 control-label font-weight-bold text-danger"><?= _("Proveedor (Distribuidor)") ?></label>
                                    <div class="col-sm-8">
                                      <?= form_dropdown('company_id', $this->app->get_suppliers(true), $company_id, "id='company_id' class='form-control' required disabled readonly"); ?>
                                          <div class="invalid-feedback">
                                              <p><?= _("Ingrese proveedor o distribuidor del item.") ?></p>
                                          </div>
                                    </div>
                                    </div>
                                 <?php endif; ?>

                                 <div id="upload_section" style="display: visible">
                                   <div class="form-group row mb-2">
                                      <div class="col-sm-3">
                                          <label for="file_name"><?= _("Archivo a subir") ?></label>
                                      </div>
                                      <div class="col-sm-6">
                                          <input name="file_name" class="form-control" id="file_name" readonly placeholder="<?= _("Nombre Archivo") ?>">
                                      </div>
                                      <div class="col-sm-3">
                                          <label class="btn btn-primary form-control">
                                               <?= _("Buscar..") ?>
                                               <input type="file" class="custom-file-input"  name="file_media_src" hidden>
                                          </label>
                                      </div>
                                   </div>
                                 </div>

                            </div>
                          </div>

                          <!--- SAVE BUTTON OUT OF THE TABS !-->
                          <div class="form-group row mb-2 mt-3">
                                <div class="col-sm-3 offset-3">
                                    <button type="submit" id="submitButton" class="btn btn-primary"><?= _("Subir y Procesar") ?></button>
                                </div>
                          </div>

                          <?= form_close() ?>

                      </div>
                    </div>
               </div>
           </div>
       </div>





<script>

 $('.custom-file-input').on('change', function() {
     var fname = $('[name=file_media_src]').val().split('\\').pop();
     $('#file_name').val(fname);
  });

</script>
