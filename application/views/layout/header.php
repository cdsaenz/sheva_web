
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <!-- cs:mobile pwa required, choose a theme color (same as navbar IDEALLY) !-->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#007bff" />

    <!--- ADDED 26/12/19 FOR ISSUES WITH A VIEW NOT UPDATING  -->
    <meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1997 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <!-- cs:manifest poner en root idealmente -->
    <link rel="manifest" href="<?= base_url('manifest.json?v=1.2') ?>" />
    <!-- cs:favicon for mobile !-->
    <link rel='shortcut icon' type='image/x-icon' href='<?= base_url('favicon.ico?v=1.0')  ?>' />
    <!-- cs:Every page can set its own title, default is app_name !-->
    <title><?= isset($title) ? "$title &raquo; " . config_item("app_name") : config_item("app_name") ?></title>
    <!--  MAIN FONT: Roboto, also internal -->
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <!-- FONTAWESOME 5.2-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <!-- Bootstrap 4.0 -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css?v=4.0.0_1')  ?>">
    <!-- JQUERY UI/AUTOCOMPLETE -->
    <link rel="stylesheet" href="<?= base_url('assets/css/jquery-ui.min.css?v=1')  ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/jquery-ui.theme.min.css?v=1')  ?>">

    <!-- JQUERY DATATABLES -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>

    <!--  csdev footer -->
    <link rel="stylesheet" href="<?= base_url('assets/css/csfooter.css?v=1.2')  ?>">
    <!--  csdev estilo (al final)-->
    <link rel="stylesheet" href="<?= base_url('assets/css/csdev.css?v=1.2')  ?>">

    <style type="text/css">
       /* Body reset should be declared in css/scss */
       @media (max-width: 768px){
            body {padding-top: 50px};
        }
    </style>


</head>
