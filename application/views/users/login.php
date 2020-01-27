<!------------------------------------->
<!-- USERS/LOGIN VIEW                -->
<!-- version 5.0                     -->
<!-- CDS, 9-MAY-19                   -->
<!------------------------------------->

<div class="container-fluid mt-2 mb-2">
   <div class="row">
        <div class="col-xs-12 col-lg-12 text-center loading" style="display:none"></div>
   </div>
   <div class="row">
       <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto">
            <div class="card mt-3 shadow">
              <div class="card-header text-center cs-view-header">
                  <h3><i class="fas fa-user-circle mr-2"></i><?= _("Bienvenido") ?></h3>
              </div>
              <div class="card-body cs-view-body">
                   <?= form_open("users/dologin","class='form-horizontal needs-validation' novalidate") ?>
                      <div class='form-group'>
                          <label for='nick_mail' class='col-sm-12 control-label'><?= _("Alias o Email") ?></label>
                          <div class='col-sm-12'>
                              <input type="text" name="nick_mail" value=""  id='nick_mail' class='form-control' placeholder='<?= _("Ingrese usuario") ?>' required />
                              <div class="invalid-feedback">
                                  <p><?= _("Debe ingresar email o alias para ingresar.") ?></p>
                              </div>
                          </div>
                      </div>
                      <div class='form-group'>
                          <label for='user_pwd' class='col-sm-12 control-label'><?= _("Contrase&ntilde;a") ?></label>
                          <div class='col-sm-12'>
                              <input type="password" name="user_pwd" value=""  id='user_pwd' class='form-control' placeholder='<?= _("Ingrese su contrase&ntilde;") ?>'  required />
                              <div class="invalid-feedback">
                                  <p><?= _("Debe ingresar su contrase&ntilde;a") ?></p>
                              </div>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-sm-12">
                              <button id="submitButton" type="submit" class="btn btn-lg btn-primary btn-block"><?= _("Ingresar") ?></button>
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
