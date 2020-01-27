

       <div class="container-fluid">

             <div class="row">
                <div class="col-xs-12 col-lg-12 text-center loading" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-xs-12 col-lg-8">
                    <div class="card mt-3">
                      <div class="card-body">
                          <div class="card-title form-title">
                              <h4>
                                  <i class="fas fa-book mr-2"></i><?= $title ?>
                              </h4>
                              <p><small><?=_("Ingrese multiples codigos, uno por linea") ?></small></p>
                          </div>

                            <?= form_open("codes/domultiple","class='form-horizontal needs-validation' novalidate",$hidden) ?>
                              <div class="form-group row mb-2">
                                  <label for="code_field" class="col-sm-4 control-label font-weight-bold"><?= _("Tipo de Tabla") ?></label>
                                  <div class="col-sm-8">
                         			    <?= form_dropdown('code_field', $this->codes_model->get_field_types(TRUE),"", "class='form-control' required"); ?>
                                        <div class="invalid-feedback">
                                            <p><?= _("Debe seleccionar un tipo de tabla") ?></p>
                                        </div>
                                  </div>
                              </div>

                              <div class="form-group row mb-2">
                                  <label for="code_list" class="col-sm-4 control-label"><?= _("Ingrese codigos uno por linea") ?></label>
                                  <div class="col-sm-8">
                                      <textarea id="code_list" name="code_list" class="form-control" rows="10"></textarea>
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





<script>

  // JavaScript for disabling form submissions if there are invalid fields
  (function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {

            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            else {
                // valido correctamente, mostrar waiting things.
                $(".loading").show();
                $("#submitButton").removeClass("btn-primary").addClass("btn-warning").prop("disabled", true);
            }
            form.classList.add('was-validated');
        }, false);
        });
    }, false);
  })();

</script>
