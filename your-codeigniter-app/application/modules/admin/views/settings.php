 <!-- Main content -->
      <section class="content">
         <?php if(!empty(validation_errors()) || !empty($this->session->flashdata('error'))): ?>
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
    <?php echo validation_errors(); ?>
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
                
                <?php 
                  $vConsumerKey='';
                  $vConsumerSecret='';
                  $vConsumerToken='';
                  $vConsumerTokenSecret='';
                  $vFirstName='';
                  $vLastName='';
                  $vEmail='';
                 

                 // print_r($settings);die;
                  if(!empty($settings))
                  {
                    $vConsumerKey=$settings->vConsumerKey;
                    $vConsumerSecret=$settings->vConsumerSecret;
                    $vConsumerToken=$settings->vConsumerToken;
                    $vConsumerTokenSecret=$settings->vConsumerTokenSecret;
                    
                  }
                  
                  if(!empty($account))
                  {
                    
                    $vFirstName=$account->vFirstName;
                    $vLastName=$account->vLastName;
                    $vEmail=$account->vEmail;
                  }
                  
                ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Account Settings</h3>
          </div>
          <div class="box-body">
              <?php echo form_open('admin/settings/save_config');?>
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="vFirstName">First Name</label>
                    <input type="text" class="form-control" value="<?=$vFirstName;?>" name="vFirstName" />  
                  </div>
                  <div class="form-group">
                    <label for="vLastName">Last Name</label>
                    <input type="text" class="form-control" name="vLastName" value="<?=$vLastName?>" />  
                  </div>
                  <div class="form-group">
                    <label for="vEmail">Email</label>
                    <input type="text" class="form-control" name="vEmail" value="<?=$vEmail?>" />  
                  </div>
                  <div class="form-group">
                    <label for="vPassword">New Password</label> <small class="error">(Leave blank if you do not wish to change)</small>
                    <input type="text" class="form-control" name="vPassword" />  
                  </div>
                  <div class="form-group">
                    <label for="vPassword">New Confirm Password</label> <small class="error">(Leave blank if you do not wish to change)</small>
                    <input type="text" class="form-control" name="vConfirmPassword" />  
                  </div>
                </div>
                
              

              
                </div>
                <div class="box-footer">
                      <button type="submit" class="btn btn-success">Save</button>
                </div>
              </form>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->