<!------------------------->
<!-- main div            -->
<!------------------------->

    <div class="container-fluid">
         <div class="card mt-3 shadow cs-list-header">
              <div class="card-body">
                  <h3><i class="fa fa-file-invoice mr-2"></i><?= $title ?>
                      <p class="small"><?= $count . _(" archivo(s) hallado(s)."); ?></p>
                  </h3>
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
                  <?= form_open("media/search",'class="form-horizontal" id="searchForm" role="form"') ?>
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
           <table class="table table-hover table-sm shadow table-dark cs-list-table">
            <tr>
                <thead class="thead-dark">
                    <th><?php echo _("ID") ?></th>
                    <th><?php echo _("Nombre/Fuente") ?></th>
                    <th><?php echo _("Medio/Tipo") ?></th>
                    <th><?php echo _("Principal") ?></th>
                </thead>
            </tr>
            <tbody>
                 <?php foreach ($dataset as $row): ?>
					           <tr>
					               <?php $id = $row["id"] ?>
                         <td class>
                            <a class="btn btn-info btn-sm" href="<?= base_url("media/view/$id") ?>" role="button">
                                      <span class="fas fa-edit mr-1" aria-hidden="true"></span></a>
                         </td>
					               <td><?= $row['media_name'] ?><br />
                           <div class="container">
                             <div class="row">
                               <div class="col-md-6 border shadow mb-2 mt-2 p-3 h-100 text-center">
                                 <?php if (is_image($row['media_src'])): ?>
                                     <a href="<?= default_img($row); ?>" target="_blank">
                                        <img src="<?= default_img($row);?>" alt="picture" class="img-fluid" style="height: 100px; object-fit: cover;">
                                     </a>
                                 <?php else: ?>
                                     <a href="<?= default_img($row);  ?>" target="_blank">Link a archivo adjunto</a>
                                 <?php endif; ?>
                               </div>
                             </div>
                           </div>
                         </td>
					               <td><?= $this->app->get_code_label("media_type",$row['media_type']) ?><br />
                             <?= $this->app->get_code_label("src_type",$row['src_type']) ?></td>
                         <td><?= yesno($row['is_main']) ?></td>
					           </tr>
                <?php endforeach ?>
           </tbody>
         </table>
       </div>
     </div>
