<!------------------------->
<!-- main div            -->
<!------------------------->
     <div class="container-fluid">
         <div class="card mt-3 shadow cs-list-header">
              <div class="card-header">
                <h3><i class="fas fa-tools mr-2"></i><?= $title ?>
                    <p class="small"><?= $count . _(" Codigos hallados.");?></p>
                </h3>

                <div class="row">
                    <div class="col mx-auto text-center">
                      <a href='<?= base_url("codes/add") ?>' class='btn cs-list-btn d-block d-md-inline-block'>
                        <i class="fas fa-plus-circle mr-2"></i><?= ("Nuevo Codigo") ?></a>
                      <a href='<?= base_url("codes/multiple") ?>' class='btn cs-list-btn d-block d-md-inline-block'>
                        <i class="fas fa-swatchbook mr-2"></i><?= ("Carga Multiple") ?></a>
                     </div>
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
                   <?= form_open("codes/search",'class="form-horizontal" id="searchForm" role="form"') ?>
                   <div class="form-group row">
                      <label class="col-md-3 col-sm-12 mt-1 col-form-label"><?= _("Codigo") ?></label>
                      <input name="keyword" class="form-control col-md-8 col-xs-12 ml-2" id="keyword" title="<?= _("Codigo") ?>"
                         placeholder="<?= _("Codigo") ?>" value="<?= isset($vars['keyword']) ? $vars['keyword'] : '' ?>">
                   </div>

                   <div class="form-group row">
                     <label class="col-md-3 col-sm-12 mt-1 col-form-label"><?= _("Tipo") ?></label>
                      <?= form_dropdown('tipo',$this->codes_model->get_field_types(true, "Tipo de Tabla"),
                                        isset($vars['tipo']) ? $vars['tipo'] : '',
                                        "class='custom-select form-control col-md-8 col-xs-12 ml-2' id='tipo'"
                                        );
                      ?>
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

          <div class="cs-list-paginator"><?= $links;?></div>
       </div>


       <div class="container-fluid">
         <div class="table-responsive">
           <table class="table table-hover table-sm shadow table-dark cs-list-table">
              <tr>
                  <thead class="thead-dark">
                      <th><?php echo _("ID") ?></th>
                      <th><?php echo _("Tipo") ?></th>
                      <th><?php echo _("Codigo") ?></th>
                      <th><?php echo _("Orden") ?></th>
                  </thead>
              </tr>
              <tbody>
               <?php foreach ($dataset as $row):?>
		              <tr>
		                  <?php $id = $row["id"] ?>
		                  <td>
                        <span>
				                    <a class="btn btn-info btn-sm" style="width: 60px"  href="<?= base_url("codes/view/$id") ?>" role="button">
  							               <?= $id ?></a>
				                </span>
		                  </td>
            					<td><?= $row['code_field'] ?></td>
            					<td><?= $row['code_value'] ?></td>
            					<td><?= $row['code_number'] ?></td>
		              </tr>
              <?php endforeach ?>
             </tbody>
          </table>
        </div>
      </div>

    <script>
    /* prevent refresh/back issue*/
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
