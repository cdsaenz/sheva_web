<!------------------------------------->
<!-- START/INDEX Main View           -->
<!-- CDS, 9-MAY-19                   -->
<!------------------------------------->
   <style>

       .bg-blurry-text {
         background-color: rgb(0,0,0); /* Fallback color */
         background-color: rgba(0,0,0, 0.4); /* Black w/opacity/see-through */
         color: white;
         border: 2px solid #f1f1f1;
         top: 10%;
         left: 50%;
         transform: translate(-50%, -10%);
         z-index: 1;
         width: 80%;
         padding: 20px;
         text-align: center;
       }

   </style>

    <div class="jumbotron mt-2 m-xs-2 m-md-5 bg-cover">
      <div class="row">
        <div class="col-md-12 mx-auto text-center">
          <div class="container">
            <div class="row">
                <div class="col bg-blurry-text">
                  <h1 class="display-4 font-weight-bolder"><?= config_item("app_name")?></h1>
                  <h2 class="lead"><?= config_item("app_desc")?></h2>
                </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <?php if ($this->app->is_admin()): ?>
            <div class="container">
              <div class="row mt-2 mb-3">
                <div class="col-md-12 col-sm-12 mx-auto justify-content-center text-center d-flex my-auto">
                    <div class="btn btn-block p-2 rounded shadow text-white" style="text-decoration: none !important; background-color: rgb(212, 89, 100)">
                      <i class="fa fa-key mr-2"></i>Acceso Administrador
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 col-xs-12 mx-auto text-center">
                  <div class="btn-group" role="group" aria-label="menu">
                      <div class="row">
                        <div class="col-md-6 col-xl-3 col-xs-12 mb-1">
                          <a href="<?= base_url("items")  ?>" style="text-decoration: none !important" class="btn shadow action-button-big">
                            ITEMS</a>
                        </div>

                        <div class="col-md-6 col-xl-3 col-xs-12 mb-1">
                          <a href="<?= base_url("suppliers")  ?>" style="text-decoration: none !important" class="btn shadow action-button-big">
                            PROVEEDORES</a>
                        </div>

                        <div class="col-md-6 col-xl-3 col-xs-12 mb-1">
                          <a href="<?= base_url("customers")  ?>" style="text-decoration: none !important" class="btn shadow action-button-big">
                            CLIENTES</a>
                        </div>

                        <div class="col-md-6 col-xl-3 col-xs-12 mb-1">
                          <a href="<?= base_url("codes")  ?>" style="text-decoration: none !important" class="btn shadow action-button-big">
                            TABLAS</a>
                        </div>


                      </div>
                  </div>
                </div>
              </div>

            </div>
          <?php elseif ($this->app->is_supp()): ?>
            <div class="container">
              <div class="row mt-2 mb-3">
                <div class="col-md-12 col-sm-12 mx-auto justify-content-center text-center d-flex my-auto">
                    <div class="btn btn-block p-2 rounded shadow text-white" style="text-decoration: none !important; background-color: rgba(69, 141, 252, 0.75)">
                      <i class="fa fa-shopping-cart mr-2"></i>Acceso Distribuidor
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 col-xs-12 mx-auto text-center">
                  <div class="btn-group" role="group" aria-label="menu">
                      <div class="row">
                        <div class="col-md-6 col-xl-3 col-xs-12 mb-1">
                          <a href="<?= base_url("myitems")  ?>" style="text-decoration: none !important; background-color: rgba(66, 139, 246, 0.61)"
                            class="btn shadow action-button-big">
                            <i class="fa fa-cogs mb-2"></i><br />
                              <?= _("MIS ITEMS") ?>
                            </a>
                        </div>

                        <div class="col-md-6 col-xl-3 col-xs-12 mb-1">
                          <a href="<?= base_url("mycustomers")  ?>" style="text-decoration: none !important ; background-color: rgba(66, 139, 246, 0.61)"
                            class="btn shadow action-button-big">
                            <i class="fa fa-address-card mb-2"></i><br />
                              <?= _("MIS CLIENTES") ?>
                            </a>
                        </div>

                        <div class="col-md-6 col-xl-3 col-xs-12 mb-1">
                          <a href="<?= base_url("registrations")  ?>" style="text-decoration: none !important; background-color: rgba(66, 139, 246, 0.61)"
                            class="btn shadow action-button-big">
                              <i class="fa fa-user mb-2"></i><br />
                                <?= _("REGISTRACIONES") ?>
                              </a>
                        </div>

                        <div class="col-md-6 col-xl-3 col-xs-12 mb-1">
                          <a href="<?= base_url("myorders")  ?>" style="text-decoration: none !important; background-color: rgba(66, 139, 246, 0.61)"
                            class="btn shadow action-button-big">
                            <i class="fa fa-shopping-cart mb-2"></i><br />
                              <?= _("MIS ORDENES") ?>
                            </a>
                        </div>
                      </div>
                  </div>
                </div>
              </div>

            </div>
          <?php endif ?>
        </div>

      </div>



    </div>
