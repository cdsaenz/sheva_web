<!------------------------->
<!-- main div            -->
<!------------------------->

      <div class="container-fluid">
         <div class="card mt-3 shadow cs-list-header">
              <div class="card-header">
                <h3><?= $title ?>
                    <p class="small"><?= $count . _(" registraciones(s) hallada(s).");?></p>
                </h3>
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
                  <th><?php echo _("Status") ?></th>
                  <th><?php echo _("Cliente/<br />Email") ?></th>
              </thead>
          </tr>
          <tbody>
               <?php foreach ($dataset as $row): ?>
				           <tr>
				               <?php $id = $row["id"] ?>
                       <td class>
                          <a class="btn btn-info btn-sm" href="<?= base_url("registrations/view/$id") ?>" role="button">
                                    <span class="fas fa-edit mr-1" aria-hidden="true"></span></a>
                                    <span class="ml-2"><?= $id ?></span>
                       </td>
                       <td><?= $row['reg_status'] ?></td>
                       <td><?= $row['cust_name'] ?><br />
                          <?= $row['usr_email_addr'] ?>
                      </td>
				           </tr>
              <?php endforeach ?>
         </tbody>
       </table>
     </div>
   </div>
