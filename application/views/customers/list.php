<!------------------------->
<!-- main div            -->
<!------------------------->

      <div class="container-fluid">
         <div class="card mt-3 shadow cs-list-header">
              <div class="card-header">
                <h3><i class="fa fa-address-card mr-2"></i><?= $title ?>
                    <p class="small"><?= $count . _(" cliente(s) hallado(s).");?></p>
                </h3>

                <div class="row">
                    <div class="col mx-auto text-center">
                      <a href='<?= base_url("customers/add") ?>' class='btn cs-list-btn d-block d-md-inline-block'>
                        <i class="fas fa-plus-circle mr-2"></i><?= ("Nuevo Cliente") ?></a>
                      <a href='<?= base_url("customers/topdf") ?>' class='btn cs-list-btn d-block d-md-inline-block'>
                        <i class="fas fa-print mr-2"></i><?= ("Imprimir") ?></a>
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
                  <?= form_open("customers/search",'class="form-horizontal" id="searchForm" role="form"') ?>
                  <div class="form-group row">
                     <label class="col-md-3 col-sm-12 mt-1 col-form-label"><?= _("Nombre") ?></label>
                     <input name="keyword" class="form-control col-md-8 col-xs-12 ml-2" id="keyword" title="<?= _("Nombre") ?>"
                        placeholder="<?= _("Nombre") ?>" value="<?= isset($vars['keyword']) ? $vars['keyword'] : '' ?>">
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
          <table class="table table-hover table-sm shadow table-dark cs-list-table" id="dataTable">
          <tr>
              <thead class="thead-dark">
                  <th><?php echo _("ID") ?></th>
                  <th><?php echo _("Tipo") ?></th>
                  <th><?php echo _("Nombre") ?></th>
              </thead>
          </tr>
          <tbody>
               <?php foreach ($dataset as $row): ?>
				           <tr>
				               <?php $id = $row["id"] ?>
                       <td class>
                          <a class="btn btn-info btn-sm" href="<?= base_url("customers/view/$id") ?>" role="button">
                                    <span class="fas fa-edit mr-1" aria-hidden="true"></span></a>
                       </td>
				               <td><i><?= $row['cust_type'] ?></i></td>
                       <td><?= $row['cust_name'] ?></td>
				           </tr>
              <?php endforeach ?>
         </tbody>
       </table>
     </div>
   </div>
