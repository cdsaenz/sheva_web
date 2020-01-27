<!------------------------->
<!-- main div            -->
<!------------------------->
       <div class="container-fluid">
           <div class="card mt-3 shadow cs-list-header">   

                 <div class="card-header">
                   <h3><?= $title ?>
                       <p class="small"><?= $count . _(" usuario(s) hallado(s).");?></p>
                   </h3>

                   <div class="row">
                       <div class="col mx-auto text-center">
                         <a href='<?= base_url("users/add") ?>' class='btn cs-list-btn d-block d-md-inline-block'>
                           <i class="fas fa-plus-circle mr-2"></i><?= ("Nuevo Usuario") ?></a>
                    </div>
                 </div>
           </div>

           <!--- FILTER PANEL !--->
       <div class="card cs-list-filter">
          <div class="card-header shadow" id="headingOne">
             <h5 class="mb-0">
                <button class="btn btn-sm btn-danger" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <i class="fas fa-filter mr-2"></i><?= _("Filtro") ?>
                </button>
             </h5>
          </div>
          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
             <div class="card-body">
                <?= form_open("users/search",'class="form-horizontal" id="searchForm" role="form"') ?>
                <div class="form-group row">
                   <label class="col-md-3 col-sm-12 mt-1 col-form-label"><?= _("Nombre") ?></label>
                   <input name="keyword" class="form-control col-md-8 col-xs-12 ml-2" id="keyword" title="<?= _("Apellido") ?>"
                      placeholder="<?= _("Apellido") ?>" value="<?= isset($vars['keyword']) ? $vars['keyword'] : '' ?>">
                </div>

                <div class="form-group row">
                     <label class="col-md-3 col-sm-12 mt-1 col-form-label"><?= _("Activo") ?></label>
                     <?= form_dropdown('is_active',$this->users_model->get_yn_values(true,""),
                                        (isset($vars['is_active']) ? $vars['is_active'] : ''),
                                        "class='form-control custom-select col-md-8 col-sm-8 ml-2' id='is_active'"); ?>
                </div>
                <div class="form-row">
                   <div class="offset-md-3">
                      <button type="submit" class="btn btn-primary"><?= _("Buscar") ?></button>
                      <button type="button" class="btn btn-danger" id="resetFilter"><?= _("Limpiar") ?></button>
                   </div>
                </div>
                <?= form_close() ?>
             </div>
          </div>
         </div>

          <div class="cs-list-paginator"><?= $links; ?></div>
       </div>


       <div class="container-fluid">
         <div class="table-responsive">
           <table class="table table-hover table-sm shadow table-dark cs-list-table">
                  <tr>
                      <thead class="thead-dark">
                          <th><?php echo _("Detalle") ?></th>
                          <th><?php echo _("Alias/Activo") ?></th>
                          <th><?php echo _("Nombres/Email") ?></th>
                      </thead>
                  </tr>
                  <tbody>
                   <?php foreach ($dataset as $row): ?>
    				           <tr>
    					            <?php $id = $row["id"] ?>
          				        <td class>
    						              <a class="btn btn-info btn-sm" href="<?= base_url("users/view/$id") ?>" role="button">
    								                    <span class="fas fa-edit mr-1" aria-hidden="true"></span></a>
    		   			          </td>
    					            <td><?= $row['nick'] ?  $row['nick'] : "<i>Faltante</i>"; ?><br>
                              <?= yesno($row['is_active']) ?>
                          </td>
                   		     <td><?= $row['last_name'] . " " . $row['first_name'];?><br>
                                   <small><?= mailto($row['email']) ?></small>
                          </td>

    				          </tr>
                  <?php endforeach ?>
                 </tbody>

           </table>
        </div>
      </div>

 <script>

    $('#is_active option[value="_CHOOSE_"]').attr('disabled','disabled');

    /* prevent refresh/back issue*/
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }


 </script>
