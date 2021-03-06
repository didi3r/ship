<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <base href="<?php echo base_url() ?>">

    <title>Bioleafy :: Gestión de Ventas</title>

    <link rel="shortcut icon" type="image/png" href="<?php assets_url() ?>img/favicon.png">

    <!-- Bootstrap Core CSS -->
    <link href="<?php assets_url() ?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php assets_url() ?>bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php assets_url() ?>css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php assets_url() ?>dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="<?php assets_url() ?>css/style.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php assets_url() ?>bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- jQuery -->
    <script src="<?php assets_url() ?>bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Angular Directives for Chart.js -->
    <link href="<?php assets_url() ?>bower_components/angular-chart.js/dist/angular-chart.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper" ng-app="MoringaApp">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">
                    <img src="<?php assets_url() ?>img/logobioleafy_small.png" style="max-width: 100px" alt="Bioleafy">
                </a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <span class="hidden-xs"><?php echo $this->authentication->read('username') ?></span>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="<?php echo site_url('settings') ?>"><i class="fa fa-cogs fa-fw"></i> Configuración</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('login/logout') ?>"><i class="fa fa-sign-out fa-fw"></i> Cerrar Sesión</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <?php $this->load->view('main_menu') ?>
        </nav>