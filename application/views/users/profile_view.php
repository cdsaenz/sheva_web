<!------------------------------------->
<!-- USERS/MYPROFILE VIEW            -->
<!-- CDS, 9-MAY-19                   -->
<!------------------------------------->

 <div class="container-fluid">
     <div class="row">
         <div class="col-12 col-md-8 col-lg-6 mx-auto">
              <div class="card mt-3 shadow">
                <div class="card-header cs-view-header">
                    <h2><i class="fas fa-user-check mr-2"></i><?= _("Mi Perfil") ?></h2>
                    <span class="badge badge-info <?= $status ?> p-2">
                        <?= $status ?>
                     </span>
                </div>
                <div class="card-body cs-view-body">

                    <?= form_open('', 'class="form-horizontal"'); ?>
                         <div class="form-group row">
                              <label class="col-sm-4 control-label"><?= _("Alias") ?></label>
                              <div class="col-sm-8">
                      				<p class="form-control-static"><b><?= $row['nick'] ?></b></p>
                              </div>
                         </div>

                         <div class="form-group row">
                              <label class="col-sm-4 control-label"><?= _("Nombre") ?></label>
                              <div class="col-sm-8">
                      				<p class="form-control-static"><?= $row['first_name'] ?></p>
                              </div>
                         </div>

                         <div class="form-group row">
                                <label class="col-sm-4 control-label"><?= _("Apellido") ?></label>
                                <div class="col-sm-8">
                        				<p class="form-control-static"><?= $row['last_name'] ?></p>
                                </div>
                         </div>

                         <div class="form-group row">
                                <label class="col-sm-4 control-label"><?= _("Email") ?></label>
                                <div class="col-sm-8">
                        				<p class="form-control-static"><?= $row['email'] ?></p>
                                </div>
                         </div>


                   <?= form_close() ?>

                   <div class="row">
                       <div class="col-6"><p><?= anchor("/users/pwd",_("Cambiar contrase&ntilde;a?"))  ?></p></div>
                   </div>
                </div>

          </div>
      </div>
     </div>

 </div>
