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
                      <a href='<?= base_url("mycustomers/topdf") ?>' class='btn cs-list-btn d-block d-md-inline-block'>
                        <i class="fas fa-print mr-2"></i><?= ("Imprimir") ?></a>
                     </div>
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
                          <a class="btn btn-info btn-sm" href="<?= base_url("mycustomers/view/$id") ?>" role="button">
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
