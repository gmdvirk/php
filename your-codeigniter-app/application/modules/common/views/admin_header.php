<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title><?=$pageTitle;?></title>
            <!-- Tell the browser to be responsive to screen width -->
            <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <!-- Bootstrap 3.3.7 -->
            <link rel="stylesheet" href="<?=base_url('assets');?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
            <!-- Font Awesome -->
            <link rel="stylesheet" href="<?=base_url('assets');?>/bower_components/font-awesome/css/font-awesome.min.css">
            <!-- Ionicons -->
            
            <link rel="stylesheet" href="<?=base_url('assets');?>/bower_components/Ionicons/css/ionicons.min.css">


            <!-- Theme style -->
            <link rel="stylesheet" href="<?=base_url('assets');?>/bower_components/select2/dist/css/select2.min.css">

            <link rel="stylesheet" href="<?=base_url('assets');?>/dist/css/AdminLTE.min.css">
            <link rel="stylesheet" href="<?=base_url('assets');?>/dist/css/custom.css">
            <!-- AdminLTE Skins. Choose a skin from the css/skins
                folder instead of downloading all of them to reduce the load. -->
            <link rel="stylesheet" href="<?=base_url('assets');?>/dist/css/skins/_all-skins.min.css">

            <!-- DataTables -->
            <link rel="stylesheet" href="<?=base_url('assets');?>/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

            
            <!-- Google Font -->
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
            <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
            <style>
                .active {
                    background-color:#337ab7;
                }
            </style>
            <script> var APP_URL = '<?php echo base_url(); ?>' ;</script>
        </head>
        <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
        <body class="hold-transition skin-blue layout-top-nav">
            <div class="wrapper">
                <header class="main-header">
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="">Admin Panel</a>
                            </div>
    
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li <?= $this->router->fetch_class() == 'customers' ? 'class="active"' : ''; ?>><a href="<?=base_url('admin/customers');?>"> <i class="fa fa-users"></i> Users</a></li>
                                    <li <?= $this->router->fetch_class() == 'roles' ? 'class="active"' : ''; ?>><a href="<?=base_url('admin/roles');?>"> <i class="fa fa-address-book"></i> Role</a></li>
                                    <li <?= $this->router->fetch_class() == 'units' ? 'class="active"' : ''; ?>><a href="<?=base_url('admin/units');?>"> <i class="fa fa-balance-scale"></i> Unit</a></li>
                                    <li <?= $this->router->fetch_class() == 'devices' ? 'class="active"' : ''; ?>><a href="<?=base_url('admin/devices');?>"> <i class="fa fa-bars"></i> Badges</a></li>
                                    <li <?= $this->router->fetch_class() == 'readers' ? 'class="active"' : ''; ?>><a href="<?=base_url('admin/beacons');?>"> <i class="fa fa-file"></i> Beacons</a></li>
                                    <li <?= $this->router->fetch_class() == 'events' ? 'class="active"' : ''; ?>><a href="<?=base_url('admin/events');?>"> <i class="fa fa-bars"></i> Events</a></li>
                                    <!-- <li><a href="<?php //base_url('admin/prox');?>"> <i class="fa fa-bars"></i> Prox</a></li> -->
                                    <li <?= $this->router->fetch_class() == 'summary' ? 'class="active"' : ''; ?>><a href="<?=base_url('admin/summary');?>"> <i class="fa fa-id-card"></i> Summary</a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gears"></i> Settings <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?=base_url('/admin/settings');?>">Account Settings</a></li>
                                            <li><a href="<?=base_url('/admin/invite');?>">Managers</a></li>
                                        </ul>
                                    </li>
                                    <li <?= $this->router->fetch_class() == 'gateway' ? 'class="active"' : ''; ?>><a href="<?=base_url('admin/gateway');?>"> <i class="fa fa-user-plus"></i> Gateway</a></li>
                                    
                                </ul>
                                <ul class="nav navbar-nav navbar-right">
                                    <li class="dropdown user user-menu">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <img src="<?=base_url('assets');?>/dist/img/logo.jpeg" class="user-image" alt="User Image">
                                            <span class="hidden-xs"> <?=$this->session->userdata('vName');?></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="user-header">
                                                <img src="<?=base_url('assets');?>/dist/img/logo.jpeg" class="img-circle" alt="User Image">
                                                <p> <?=$this->session->userdata('vName');?> </p>
                                            </li>
                                            <li class="user-footer">
                                                <div class="pull-right">
                                                    <a href="<?=base_url('admin/logout');?>" class="btn btn-default btn-flat">Sign out</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="navbar-custom-menu">
                            </div>
                        </div>
                    </nav>

                </header>
                <!-- Full Width Column -->
                <div class="content-wrapper">
                    <div class="container">
                    <!-- Content Header (Page header) -->
