 <!-- Main content -->
<?php 
    $vLastName=set_value('vLastName');
    $vFirstName=set_value('vFirstName');
    $iPacts=set_value('iPacts');
    $vPassportType=set_value('vPassportType');
    $vOtherPassport=set_value('vOtherPassport');
    $vPso=set_value('vPso');
    $dDateReceived=set_value('dDateReceived');
    $dDateReturned=set_value('dDateReturned');
    $vNotes=set_value('vNotes');
    $id='';
   if(!empty($passport)):
   // echo '<pre>';print_r($passport);die;
    $id=$passport->id;
    $vLastName=$passport->LastName;
    $vFirstName=$passport->FirstName;
    $iPacts=$passport->PACTS;
    $vPassportType=$passport->Passport_Type;
    $vOtherPassport=$passport->Other_Passport;
    $vPso=$passport->PSO;
    $dDateReceived=$passport->Date_Received;
    $dDateReturned=$passport->Date_Returned;
    $vNotes=$passport->Notes;
   endif;

?>
      <section class="content">
        <?php if(!empty($this->session->flashdata('success'))) : ?>
        <div class="alert alert-success alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <p><?=$this->session->flashdata('success');?>
        </div>
      <?php endif; ?>
       <?php if(!empty($this->session->flashdata('errors'))) : ?>
        <div class="alert alert-danger alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-cross"></i> Error!</h4>
            <p><?=$this->session->flashdata('errors');?>
        </div>
      <?php endif; ?>
                
        <div class="box box-default">

          <div class="box-header with-border">
            <h3 class="box-title"><?=$pageTitle;?></h3>
            
          </div>
          <div class="box-body">
           <?=form_open($action);?>
            <input type="hidden" name="id" value="<?=$id;?>" />
              <div class="col-md-6">
                  <div class="form-group">
                      <label>Last Name</label>
                      <input type="text" class="form-control" name="vLastName" value="<?=$vLastName;?>" />
                  </div>
                  <div class="form-group">
                      <label>First Name</label>
                      <input type="text" class="form-control" name="vFirstName"  value="<?=$vFirstName;?>" />
                  </div>
                  <div class="form-group">
                      <label>Pacts</label>
                      <input type="text" class="form-control" name="iPacts" value="<?=$iPacts;?>" />
                  </div>
                  <div class="form-group">
                      <label>Passport Type</label>
                      <input type="text" class="form-control" name="vPassportType" value="<?=$vPassportType;?>" />
                  </div>
                  <div class="form-group">
                      <label>Other Passport</label>
                      <input type="text" class="form-control" name="vOtherPassport" value="<?=$vOtherPassport;?>" />
                  </div>

              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label>PSO</label>
                      <input type="text" class="form-control" name="vPso" value="<?=$vPso;?>" />
                  </div>
                  <div class="form-group">
                      <label>Date Recieved</label>
                      <input type="text" class="form-control" name="dDateReceived" value="<?=$dDateReceived;?>" />
                  </div>
                  <div class="form-group">
                       <label>Date Returned</label>
                      <input type="text" class="form-control" name="dDateReturned" value="<?=$dDateReturned;?>" />
                  </div>
                  <div class="form-group">
                      <label>Notes</label>
                      <textarea name="vNotes" class="form-control" rows="5"><?=$vNotes;?></textarea>
                  </div>
              </div>
            
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
              <a href="<?=base_url('customers/passports/');?>" class="btn btn-danger btn-flat pull-left">Cancel</a>
              <input type="submit" value="Save" class="btn btn-success btn-flat pull-right" />
          </div>
          </form>
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->