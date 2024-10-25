<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Registration</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?=base_url('assets/');?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url('assets/');?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?=base_url('assets/');?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url('assets/');?>dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=base_url('assets/');?>plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
  .vError{color: #AF2323}
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Manager</b> Registertion</a>
  </div>
  <!-- /.login-logo -->
  <?php if(!empty($this->session->flashdata('error'))): ?>
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
    <?php echo $this->session->flashdata('error'); ?>
  </div>
  <?php endif; ?>
  <?php if(!empty($this->session->flashdata('success'))): ?>
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-check"></i> Success!</h4>
    
    <?php echo $this->session->flashdata('success'); ?>
  </div>
  <?php endif; ?>
      
  <div class="login-box-body">

    <p class="login-box-msg">Register to create account.</p>

    <?php echo form_open('managers/create_manager');?>
      <div class="form-group has-feedback">

		  
		<?=form_error("vFirst", "<span class='vError'>","</span>");?>
        <input type="text" name="vFirst" class="form-control" placeholder="First Name">
        <span class="glyphicon form-control-feedback"></span>
      </div>
        <div class="form-group has-feedback">
			<?=form_error("vLast", "<span class='vError'>","</span>");?>
        <input type="text" name="vLast" class="form-control" placeholder="Last Name">
        <span class="glyphicon form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
		  <?=form_error("vEmail", "<span class='vError'>","</span>");?>
        <input type="text" name="vEmail" class="form-control" value="<?php if($this->session->userdata('email'))echo $this->session->userdata('email');?>" readonly placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
       <div class="form-group has-feedback">
		  <?=form_error("facility", "<span class='vError'>","</span>");?>
        <input type="text" name="vFacility" class="form-control" value="<?php if($this->session->userdata('facility'))echo $this->session->userdata('facility');?>" readonly placeholder="Facility Name">
        <span class="glyphicon form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
		 <?=form_error("vPassword", "<span class='vError'>","</span>");?>
        <input type="password" name="vPassword" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
		  <?=form_error("vPassword2", "<span class='vError'>","</span>");?>
        <input type="password" name="vPassword2" class="form-control" placeholder="Confirm Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4 pull-right">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign Up</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!--a href="#">I forgot my password</a><br>
    <a href="<?=base_url('customers/register');?>">Register as new member</a><br-->
    

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?=base_url('assets/');?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?=base_url('assets/');?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?=base_url('assets/');?>/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
   $(".alert").slideUp(500);
    });   
          
 });
</script>
</body>
</html>
