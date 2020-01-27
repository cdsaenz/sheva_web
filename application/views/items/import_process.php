

       <div class="container-fluid">

             <div class="row">
                <div class="col-xs-12 col-lg-12 text-center loading" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-xs-12 col-lg-12 alert alert-danger" id="errors" style="display:none"></div>
             </div>

             <div class="row">
                <div class="col-xs-12 col-lg-8 mx-auto">
                    <div class="card mt-3">
                      <div class="card-header cs-view-header">
                          <h4>
                              <i class="fas fa-cog mr-2"></i><?= $title ?>
                          </h4>
                      </div>
                      <div class="card-body cs-view-body">
                          <div class="row">
                             <div class="col-md-12">
                                 <?php $this->items_model->import_csv($csv_file,$company_id) ?>
                             </div>
                          </div>
                      </div>
                    </div>
               </div>
           </div>


       </div>
