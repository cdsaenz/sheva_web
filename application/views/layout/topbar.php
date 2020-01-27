
  <!--   TOPBAR      -->

  <nav class="navbar fixed-top navbar-expand-lg navbar-dark cs-topbar">
    <div>
      <?php if ($this->ctl_name != 'start') : ?>
      <a class="navbar-brand text-white" onClick="history.go(-1); return false;">
          <i class="fas fa-angle-left icon-color"></i></a>
      <?php endif ?>

      <!-- BARRA DE NAVEGACION -->
      <a class="navbar-brand" href="<?= base_url("/")?>">
          <i class="fas fa-home mr-2 icon-color"></i><span class="navbar-left">
          </span>
      </a>
    </div>

    <!-- PUBLICO: NO NECESITA LOGIN
       <ul class="navbar-nav">
         <li class="nav-item"><a class='nav-link text-white' href="/" target="_blank"><?= _("Frontpage") ?></a></li>
       </ul>
    -->

    <?php if (!$this->session->userdata('logged_in')) :  ?>
       <!-- En caso de no estar loggeado, mostrar login -->
  	  <ul class="navbar-nav ml-auto">
  			<li class="nav-item"><a class='nav-link' href="<?= base_url("users/login") ?>"><?= _("Admin") ?></a></li>
  	  </ul>
  	<?php else :  ?>
       <ul class="navbar-nav" style="position: absolute;  left: 50%;  transform: translateX(-50%);">
          <!-- En en centro nombre empresa o ADMIN -->
         <li class="nav-item text-white"><?= $this->session->user_company_name ?></li>
       </ul>
       <!-- STANDARD APP WIDE MENU -->
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
       </button>
        <!-- Esta loggeado. MOSTRAR menues - hacer collapsable  -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
               <!-- All menus -->
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= _("Sistema") ?></a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class='dropdown-item' href="<?= base_url("users/myprofile")?>"><?= _("Mi Perfil") ?></a>
                      <?php if ($this->session->userdata('is_admin') == 'Y') :  ?>
                          <div class="dropdown-divider"></div>
                          <a class='dropdown-item' href="<?= base_url("")?>"><?= _("Items") ?></a>
                          <a class='dropdown-item' href="<?= base_url("suppliers")?>"><?= _("Distribuidores") ?></a>
                          <a class='dropdown-item' href="<?= base_url("customers")?>"><?= _("Clientes") ?></a>
                          <div class="dropdown-divider"></div>
                          <a class='dropdown-item' href="<?= base_url("media")?>"><?= _("Archivos") ?></a>
                          <a class='dropdown-item' href="<?= base_url("users")?>"><?= _("Usuarios") ?></a>
                          <a class='dropdown-item' href="<?= base_url("codes")?>"><?= _("Tablas") ?></a>
                      <?php elseif ($this->session->userdata('is_supp') == 'Y') :  ?>
                          <a class='dropdown-item' href="<?= base_url("registrations")?>"><?= _("Registraciones") ?></a>
                          <div class="dropdown-divider"></div>
                          <a class='dropdown-item' href="<?= base_url("myitems")?>"><?= _("Mis Items") ?></a>
                          <a class='dropdown-item' href="<?= base_url("orders")?>"><?= _("Mis Ordenes") ?></a>
                          <a class='dropdown-item' href="<?= base_url("mycustomers")?>"><?= _("Mis Clientes") ?></a>
                          <div class="dropdown-divider"></div>
                          <a class='dropdown-item' href="<?= base_url("itypes")?>"><?= _("Tipos de Items") ?></a>
                      <?php endif ?>
                    </div>
                  </li>
            </ul>

            <?php if ($this->session->userdata('is_admin') == 'Y') :  ?>
              <span class="navbar-text badge badge-danger text-white">
                <?= $this->session->userdata('user_nick')?>(<?= $this->session->userdata('user_company_id')?>)
              </span>
            <?php else: ?>
              <span class="navbar-text badge badge-success text-white">
                <?= $this->session->userdata('user_nick')?>(<?= $this->session->userdata('user_company_id')?>)
              </span>
            <?php endif; ?>

        	  <a class='nav-link' href='<?= base_url("users/logout") ?>' rel="tooltip" title="<?= _("Salir") ?>">
        			<i class='fas fa-sign-out-alt icon-color'></i>
        	  </a>
        </div>


  	<?php endif  ?>


  </nav>
