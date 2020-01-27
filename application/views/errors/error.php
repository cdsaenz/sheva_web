<!---
  ERROR PAGE
 -->

<div class="row mb-5 mt-5">
  <div class="col-md-6 mx-auto">
  <div class='card bg-warning shadow'>
      <div class='card-body text-center'>
            <h1 class='title font-weight-bold'>
              <i class="fa fa-exclamation-circle"></i>
              <?= $title ?>
            </h1>
            <h4><?= $error_msg ?></h4>
            <br><?= isset($back) ? $back : ""  ?>
      </div>
  </div>
  </div>
</div>
