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
  
     <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url('assets');?>/bower_components/select2/dist/css/select2.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?=base_url('assets');?>/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url('assets');?>/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?=base_url('assets');?>/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?=base_url('assets');?>/dist/css/custom.css?v=213">
 <!-- DataTables -->
  <link rel="stylesheet" href="<?=base_url('assets');?>/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
   

  </style>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="#" class="navbar-brand"><b>Customer</b> Dashboard</a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="<?=base_url('customers/dashboard');?>"><i class="fa fa-dashboard"></i>  Dashboard </a></li>          
            <!--li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
                <li class="divider"></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li-->
          </ul>
          
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="<?=base_url('assets');?>/dist/img/user2-160x160.jpg" class="user-image" alt="<?=$this->session->userdata('vName');?>">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?=$this->session->userdata('vName');?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="<?=base_url('assets');?>/dist/img/user2-160x160.jpg" class="img-circle" alt="<?=$this->session->userdata('vName');?>">

                  <p>
                    <?=$this->session->userdata('vName');?>
                  </p>
                </li>
                
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="<?=base_url('customers/settings');?>" class="btn btn-default btn-flat">Settings</a>
                  </div>
                  <div class="pull-right">
                    <a href="<?=base_url('customers/logout');?>" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
