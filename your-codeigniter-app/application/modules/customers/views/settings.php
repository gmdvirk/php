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

                 // print_r($settings);die;
                  if(!empty($settings))
                  {
                    $vConsumerKey=$settings->vConsumerKey;
                    $vConsumerSecret=$settings->vConsumerSecret;
                    $vConsumerToken=$settings->vConsumerToken;
                    $vConsumerTokenSecret=$settings->vConsumerTokenSecret;
                   
                  }
                ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Twitter API Settings</h3>
          </div>
          <div class="box-body">
          <?php echo form_open('admin/settings/save_config');?>
              <div class="box-body">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="vConsumerKey">Consumer Key</label>
                    <input type="text" class="form-control" value="<?=$vConsumerKey;?>" name="vConsumerKey" id="vConsumerKey" placeholder="Enter consumer key of twitter api">
                  </div>
                  <div class="form-group">
                    <label for="vConsumerSecret">Consumer Secret</label>
                    <input type="text" class="form-control" value="<?=$vConsumerSecret;?>" name="vConsumerSecret" id="vConsumerSecret" placeholder="Enter consumer secret of twitter api">
                  </div>
                  <div class="form-group">
                    <label for="vConsumerToken">Consumer Access Token</label>
                    <input type="text" class="form-control" value="<?=$vConsumerToken;?>" name="vConsumerToken" id="vConsumerToken" placeholder="Enter consumer access token of twitter api">
                  </div>
                  <div class="form-group">
                    <label for="vConsumerTokenSecret">Consumer Access Token Secret</label>
                    <input type="text" class="form-control" value="<?=$vConsumerTokenSecret;?>" name="vConsumerTokenSecret" id="vConsumerTokenSecret" placeholder="Enter consumer access token of twitter api">
                  </div>

                </div>
                
              </div>
              <!-- /.box-body -->

              
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