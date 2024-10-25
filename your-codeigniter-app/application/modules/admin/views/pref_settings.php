 <!-- Main content -->
      <section class="content">
 <?php if(!empty($this->session->flashdata('error'))): ?>
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

                
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"><?=$pageTitle;?></h3>
          </div>
          <div class="box-body">
              <div class="col-md-6"> 
                <h3>Save Sates</h3>
                <?php echo form_open('admin/preference/save_state'); ?>
                  <div class="form-group">
                      <label>State Name</label>
                      <input type="text" name="vStateName" id="vStateName" class="form-control" />
                  </div>
                <img src="<?=base_url();?>/assets/loader_save.gif" class="loadImage" style="display:none;width: 9%;margin-top: -10px;"> 
                <button class="btn btn-success" id="btn_updt" type="submit">Save State</button>
                </form>
                

              </div>
              <div class="col-md-6"> 
                <h3>Save Handles According to State</h3>
                <?php echo form_open('admin/preference/save_handle'); ?>
                <div class="form-group">

                    <select name="iStateId" class="form-control ">
                      <option value="">---Select State---</option>
                        <?php foreach($vStates as $vState): ?>
                          <option value="<?=$vState->iId;?>"><?=$vState->vStateName;?></option>
                        <?php endforeach; ?>
                    </select>
                    
                </div> 
                <div class="form-group">
                    <label>Screen Name</label>
                    <input type="text" name="vScreenName" id="vScreenName" placeholder="Enter Twitter screen name" class="form-control" />
                </div>
                <img src="<?=base_url();?>/assets/loader_save.gif" class="loadImages" style="display:none;width: 9%;margin-top: -10px;"> 
                <button class="btn btn-success" id="btn_upd_pref" type="submit">Save State</button>
                </form>
              </div>
              <div class="col-md-6">
              	<div style="margin-top:10%;" class="table-responsive">
                <table  class="datatable table-responsive table table-condensed table-stripped">
                  <thead>
                      <th>#</th>
                      <th>State Name</th>
                      <th>Actions</th>
                  </thead>
                  <tbody>
                      <?php 
                          $i=1;
                          if(!empty($vStates)){
                          foreach($vStates as $vState){
                      ?>
                      <tr>
                          <td><?=$i;$i++;?></td>
                          <td><?=$vState->vStateName;?></td>
                          <td><a href="javascript:void(0);"  class="btn btn-flat btn-sm btn-info editvStateName" data-iId="<?=$vState->iId;?>"><i class="fa fa-edit"></i></a><a href="<?=base_url('admin/preference/delete_state/'.$vState->iId);?>" class="btn btn-flat btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this state It will delete all the handles saved inside this state?');"><i class="fa fa-trash"></i></a></td>
                      </tr>
                    <?php } } ?>
                  </tbody>
                </table>
              </div>
              </div>
               <div class="col-md-6">
              	<div style="margin-top:10%;" class="table-responsive">
                <table  class="datatable table-responsive table table-condensed table-stripped">
                  <thead>
                      <th>#</th>
                      <th>State Name</th>
                      <th>Twitter Handle</th>
                      <th>Action</th>
                  </thead>
                  <tbody>
                      <?php 
                          $i=1;
                          if(!empty($vHandles)){
                          foreach($vHandles as $vHandle){
                      ?>
                      <tr>
                          <td><?=$i;$i++;?></td>
                          <td><?=$vHandle->vStateName;?></td>
                          <td><?=$vHandle->vScreenName;?></td>
                          <td><a href="javascript:void(0);"  class="btn btn-flat btn-sm btn-info editvScreenName" data-iId="<?=$vHandle->iId;?>"><i class="fa fa-edit"></i></a><a href="<?=base_url('admin/preference/delete_handle/'.$vHandle->iId);?>" class="btn btn-flat btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this handle?');"><i class="fa fa-trash"></i></a></td>
                      </tr>
                      <!-- Modal -->
                        
                        
                      </div>
                    <?php } } ?>
                  </tbody>
                </table>
              </div>
              </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->

      <div class="modal fade" id="edit_handles" role="dialog">
          <div class="modal-dialog">
          
          <?php echo form_open('admin/preference/update_handle');?>
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Handle</h4>
              </div>
              <div class="modal-body">
                    <div class="form-group">
                    <input type="hidden" name="iId" id="edit_iId" />
                      <label for="iStateId">Select State</label>
                      <select name="iStateId" class="form-control" id="edit_iStateId">
                        <option value="">---Select State---</option>
                        <?php foreach($vStates as $vState): ?>
                          <option value="<?=$vState->iId;?>"><?=$vState->vStateName;?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="vScreenname">Screen Name</label>
                      <input type="text" class="form-control" id="edit_vScreenName" name="vScreenName" />
                    </div>
              </div>
              <div class="modal-footer">
                
                <button type="submit" class="btn btn-flat btn-success pull-left" >Update</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
             </form>
          </div>
        </div>
         <div class="modal fade" id="edit_state" role="dialog">
          <div class="modal-dialog">
          
          <?php echo form_open('admin/preference/update_state');?>
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit State</h4>
              </div>
              <div class="modal-body">
                    <input type="hidden" name="iId" id="edit_state_iId" />
                    <div class="form-group">
                      <label for="vStateName">State Name</label>
                      <input type="text" class="form-control" id="edit_vStateName" name="vStateName" />
                    </div>
              </div>
              <div class="modal-footer">
                
                <button type="submit" class="btn btn-flat btn-success pull-left" >Update</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
             </form>
          </div>
        </div>
