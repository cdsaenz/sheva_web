<!--   FOOTER      -->

  <footer class="footer-bs cs-footer">
     <div class="row">
        <div class="col-md-4 footer-brand animated fadeInLeft">
          <h3><?= config_item("app_code") ?></h3>
          <p> <?= config_item("app_about") ?></p>
          <p> <?= config_item("app_copyright") ?></p>
          <p style="font-size: 25px">
             <a href="https://www.facebook.com/csdevar"><i class="fab fa-facebook mr-1"></i></a>
             <a href="https://m.me/csdevar"><i class="fab fa-facebook-messenger mr-1"></i></a>
             <a href="#"><i class="fab fa-linkedin"></i></a>
          </p>
        </div>
        <div class="col-md-4 footer-nav animated fadeInUp">
           <h4>MENU</h4>
           <ul class="pages">
              <li><a href="<?= base_url("start") ?>">Inicio</a></li>
              <li><a href="<?= base_url("myitems") ?>">Mis Items</a></li>
              <li><a href="<?= base_url("mycustomers") ?>">Mis Clientes</a></li>
           </ul>
        </div>
        <div class="col-md-4 footer-nav animated fadeInUp">
           <h4>LINKS</h4>
           <ul class="list">
             <li><a href="csdev.com.ar">CSDev</a></li>
           </ul>
        </div>
     </div>
     <div class="row text-center mt-3">
          <div class="col">
              Copyright 2020 - All Rights Reserved - Diseño CSDev
          </div>
     </div>
  </footer>
