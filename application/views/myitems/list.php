<!------------------------->
<!-- main div            -->
<!------------------------->

    <div class="container-fluid">
         <div class="card mt-3 shadow cs-list-header">
             <div class="card-header">
                 <h3><i class="fa fa-cogs mr-2"></i><?= $title ?>
                     <p class="small"><?= $count . _(" item(s) hallado(s)."); ?></p>
                 </h3>

                 <div class="row">
                     <div class="col mx-auto text-center">
                         <a href='<?= base_url("myitems/add") ?>' class='btn cs-list-btn d-block d-md-inline-block'>
                             <i class="fas fa-plus-circle mr-2"></i><?= ("Nuevo") ?></a>
                         <a href='<?= base_url("myitems/import") ?>' class='btn cs-list-btn d-block d-md-inline-block'>
                              <i class="fas fa-road mr-2"></i><?= ("Importar") ?></a>
                         <a href='<?= base_url("myitems/topdf") ?>' id='topdf' class='btn cs-list-btn d-block d-md-inline-block'>
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
                  <?= form_open("myitems/search",'class="form-horizontal" id="searchForm" role="form"') ?>
                  <div class="form-group row">
                     <label class="col-md-3 col-sm-12 mt-1 col-form-label"><?= _("Nombre") ?></label>
                     <input name="keyword" class="form-control col-md-8 col-xs-12 ml-2" id="keyword" title="<?= _("Nombre") ?>"
                        placeholder="<?= _("Nombre") ?>" value="<?= isset($vars['keyword']) ? $vars['keyword'] : '' ?>">
                  </div>

                  <div class="form-group row">
                       <label class="col-md-3 col-sm-12 mt-1 col-form-label"><?= _("Tipo") ?></label>
                       <?= form_dropdown('type', $types,(isset($vars['type']) ? $vars['type'] : ''),
                                               "class='form-control custom-select col-md-8 col-sm-8 ml-2' id='type'"); ?>
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

         <div class="cs-list-paginator">
              <?= $links; ?>
         </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <?php foreach ($dataset as $row): ?>
          <?php $id = $row["id"] ?>
          <div class="col-md-4 col-lg-4 col-xl-2 py-3 d-flex" style="height: 360px">
           <div class="card text-center flex-fill">
                 <img src="<?= default_img($row['main_pic'],"items") ?>" class="card-img-top img-thumbnail"
                      style="height: 150px; max-height: 150px; min-height: 150px; object-fit: cover" >
                 <div class="card-body h-100 text-white" style="background-color: black">
                  <div class="text-center mb-3">
                       <a href="<?= base_url("myitems/view/$id")?>" style='text-decoration: none; color: inherit'
                           class="btn btn-sm btn-primary">
                         <i class="fas fa-search"></i><?= $row['itm_cod'] ?>
                       </a>
                  </div>
                  <h6 style="font-size: 0.5rem"><?= $row['type_name']?></h6>
                  <h5>
                     <span class="small text-font-bold"
                           style="font:size: 2rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                       <?= $row['itm_name'] ?>
                     </span>
                  </h5>

                 </div>
            </div>
          </div>
       <?php endforeach ?>
      </div>
    </div>
