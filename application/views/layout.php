<!--
  MAIN LAYOUT VIEW
  VERSION 5.0 31/12/19
-->
<!DOCTYPE html>
<html lang="en">

<!-- HEAD SECTION !-->
<?php $this->load->view("layout/header") ?>

<body class="sections-page sidebar-collapse">

    <!-- SITE WIDE NAVBAR !-->
    <?php $this->load->view("layout/topbar") ?>

    <!-- VARIABLE CONTENT START !-->
    <div class="container-fluid">
        <div class="w-100 mb-4" id="content" style="min-height: 495px">
            <?= $content ?>
        </div>
    </div>

    <!-- SITE WIDE FOOTER !-->
    <?php $this->load->view("layout/footer") ?>

    <!-- if you put javascript includes here, page's js must be in content_bottom -->
    <!-- JQUERY cdn/popper required for bootstrap -->
    <script src="<?= base_url('assets/js/jquery-3.3.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
    <!-- BOOTSTRAP js -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- JQUERY UI -->
    <script src="<?= base_url('assets/js/jquery-ui.min.js') ?>"></script>
    <!--  moment.js required by datetimepicker -->
    <script src="<?= base_url('assets/js/moment.min.js') ?>" type="text/javascript"></script>
    <!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
    <script src="<?= base_url('assets/js/bootstrap-datetimepicker.js') ?>" type="text/javascript"></script>
    <!--	JQUERY DATATABLES  -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <!--	JQUERY DATATABLES / bootstrap addon -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <!--	JQUERY DATATABLES / responsive extension & buttons -->
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>


    <script>
        /* system wide javascript. Firebird Messaging goes here */
    </script>

    <!-- individual javascript PER VIEW - OR OTHER STUFF via variable from view -->
    <?= isset($content_bottom) ? $content_bottom : "" ?>
</body>

</html>
