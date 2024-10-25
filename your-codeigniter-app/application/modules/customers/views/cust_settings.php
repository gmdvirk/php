 <!-- Main content -->
      <section class="content">
        <?php if(!empty($this->session->flashdata('success'))) : ?>
        <div class="alert alert-success alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <p><?=$this->session->flashdata('success');?>
        </div>
      <?php endif; ?>
          <?php 
            if(!empty($customer)){
				extract((array)$customer);
            }
          ?>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Profile Settings</a></li>
             <!--  <li><a href="#tab_2" data-toggle="tab">Preference Settings</a></li> -->
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <?php echo form_open('customers/update_profile');?>
                    <div class="form-group">
                      <label>First Name</label>
                      <input type="text" value="<?=$vFirstName?>" class="form-control" name="vFirstName" />
                    </div>
                    <div class="form-group">
                      <label>Last Name</label>
                      <input type="text"  value="<?=$vLastName?>" class="form-control" name="vLastName" />
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="vEmail" class="form-control" value="<?=$vEmail?>" name="vEmail" />
                    </div>
                    <div class="form-group">
                      <label>Gender</label>
                      <select name="eGender" class="form-control">
                        <option value="M" <?=($eGender=='M')?"selected":'';?> >Male</option>
                        <option value="F" <?=($eGender=='F')?"selected":'';?> >Male</option>
                        <option value="O" <?=($eGender=='O')?"selected":'';?>>Others</option>
                      </select>
                    </div>
                    <div class="form-group">
                        <label>Password</label><small class="error">(Leave blank if you do not wish to change)</small>
                        <input type="text" class="form-control" name="password" />
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label><small class="error">(Leave blank if you do not wish to change)</small>
                        <input type="text" class="form-control" name="password" />
                    </div>
                <button type="submit" class="btn btn-info">Update</button>
                </form>
              </div>
             
            
              <div class="tab-pane" id="tab_2">
                <?php echo form_open('customers/dashboard/update_pref');?>
                  <div class="form-group">
                    <label>Select State</label>
                     <select class="form-control select2" style="width:100%;" name="pref[]" multiple="multiple" data-placeholder="Select a State"
                        >
                        <?php 
                        $pref=explode(',',$iStateIds_Pref);
                        foreach ($vStates as $vState): 

                          ?>
                            <option value="<?=$vState->iId;?>" <?php if(in_array($vState->iId,$pref)){ echo "selected";} ?> ><?=$vState->vStateName;?></option>
                        <?php endforeach; ?>
                      </select>
                  </div>
                  <button class="btn btn-success" type="submit">Save</button>
                </form>
              </div>
           
             
            </div>
           
          </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
