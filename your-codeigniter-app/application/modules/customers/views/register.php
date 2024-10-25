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
  <link rel="stylesheet" href="<?=base_url('assets');?>/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=base_url('assets');?>/plugins/iCheck/square/blue.css">


  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="#"><b>Customer</b> Registration</a>
  </div>
 <?php if(!empty(validation_errors()) || !empty($this->session->flashdata('error'))): ?>
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
    <?php echo validation_errors(); ?>
    <?php echo $this->session->flashdata('error'); ?>
  </div>
  <?php endif; ?>
      
  <div class="register-box-body">
    <p class="login-box-msg">Register a new membership</p>

    <?php echo form_open('customers/registernew'); ?>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="vFirstName" placeholder="First Name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="vLastName" placeholder="Last Name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <select name="eGender" class="form-control" required="">
          <option value="">---Select Gender---</option>
          <option value="M">Male</option>
          <option value="F">Female</option>
        </select>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="vEmail" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="vPassword" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="vConfirmPassword" placeholder="Retype password">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" required> I agree to the <a href="#">terms</a>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    

    <a href="<?=base_url('customers');?>" class="text-center">I already have a membership</a>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3 -->
<script src="<?=base_url('assets');?>/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?=base_url('assets');?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?=base_url('assets');?>/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
