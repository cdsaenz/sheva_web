

       <div class="container-fluid">

             <div class="row">
                <div class="col-xs-12 col-lg-12 text-center loading" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto login-box">
                    <div class="card mt-3 shadow">
                      <div class="card-header cs-edit-header">
                          <h4><i class="fas fa-book mr-2 mb-2"></i><?= $title ?></h4>
                      </div>
                      <div class="card-body cs-edit-body">

                            <?= form_open("users/dopwd","class='form-horizontal needs-validation' novalidate",$hidden) ?>
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

                              <div class="form-group row mb-2">
                                 <label for="pwd" class="col-sm-4 control-label font-weight-bold"><?= _("Contrase&ntilde;a") ?></label>
                                 <div class="col-sm-6">
                                   <input type="password" name="pwd" class="form-control" id="pwd" placeholder="<?= _("Contrase&ntilde;a") ?>" autocomplete="new-password"  required>
                                    <div class="invalid-feedback">
                                        <p><?= _("Debe ingresar Contrase&ntilde;a") ?></p>
                                    </div>
                                 </div>
                              </div>

                              <div class="form-group row mb-2">
                                 <label for="pwd_check" class="col-sm-4 control-label font-weight-bold"><?= _("Repetir Contrase&ntilde;a") ?></label>
                                 <div class="col-sm-6">
                                   <input type="password" name="pwd_check" class="form-control" id="pwd_check" placeholder="<?= _("Repetir Contrase&ntilde;a") ?>" autocomplete="new-password" required
                                        oninput='checkPassword()'>
                                    <div class="invalid-feedback">
                                        <p><?= _("Debe ingresar la misma Contrase&ntilde;a") ?></p>
                                    </div>
                                 </div>
                              </div>

                              <div class="form-group row mb-2">
                                    <div class="col-sm-4 offset-4 mt-2">
                                        <button type="submit" id="submitButton" class="btn btn-primary"><?= _("Cambiar Contraseña") ?></button>
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
        // clear CUSTOM validity
        form.addEventListener('submit', function(event) {
            /*if ( document.getElementById("pwd").value != document.getElementById("pwd_check").value ) {
              event.preventDefault();
              event.stopPropagation();

              //$("#pwd_check").addClass('is-invalid').removeClass('is-valid').css("");
              //$("#pwd_check").next("#invalid-feedback").find("p:first").text("Password no coincide");
              document.getElementById("pwd_check").setCustomValidity("No coinciden las claves");
              //alert("Passwords no coinciden");
            }*/
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

  function checkPassword() {
     document.getElementById("pwd_check").setCustomValidity(pwd_check.value != pwd.value ? "Las claves no coinciden" : "");
     var is_valid =document.getElementById("pwd_check").validity.valid;
     if (is_valid == false) {
        $("#pwd_check").next("#invalid-feedback").find("p:first").text("Password no coincide!!");
     }
  }

</script>
