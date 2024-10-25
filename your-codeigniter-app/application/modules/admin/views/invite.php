 <section class="content col-sm-12">
        <?php if(!empty($this->session->flashdata('success'))) : ?>
        <div class="alert alert-success alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <p><?=$this->session->flashdata('success');?>
        </div>
      <?php endif; ?>
      <?php if(!empty(validation_errors()) || !empty($this->session->flashdata('error'))): ?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-ban"></i> Alert!</h4>
				<?php echo validation_errors(); ?>
				<?php echo $this->session->flashdata('error'); ?>
			</div>
      <?php endif; ?> 
      <div class="col-sm-6">         
        <div class="box box-default">
           <div class="box-header with-border">
            <h3 class="box-title"><?=$pageTitle;?></h3>
           </div>
           <?php echo form_open('admin/invite/invitation'); ?>
            <div class="box-body">
              <div class="form-group">
					<label for="email">Enter Email</label>
				<input type="email" class="form-control" id="email"  name="email" placeholder="example@email.com" required />
              </div>
              
               <div class="form-group">
				<label for="facility"></label>
				<input type="text" class="form-control" id="facility" name="facility" placeholder="Facility Name" required />
	
              </div>
            </div>
             
			  <div class="box-footer">
				<button type="submit" class="btn btn-success">Invite</button>
				<a href="<?=base_url('admin/dashboard');?>" class="btn btn-warning pull-right">Cancel</a>
			   </div>  
		    </form> 
        </div>
        </div>
       <!---------invited------->
      <div class="col-sm-6">
         <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Invite Managers</h3>
          </div>
          <div class="box-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Email</th>
                  <th>Facility</th>
                  <th>Time</th>
                  <th>Status</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                    $i=1;
                    if(!empty($invited)):
                  foreach($invited as $inv): ?> 
                  <tr>
                    <td><?php echo $i; $i++;?></td>
                    <td><?php echo $inv->vEmail;?></td>
                    <td><?php echo $inv->vFacility;?></td>
                    <td><?php echo $inv->vTime;?></td>
                    <td><?php echo $inv->eStatus;?></td>
                  </tr>
                  <?php endforeach;?>
                  <?php endif;?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                 <th>Email</th>
                  <th>Facility</th>
                  <th>Time</th>
                  <th>Status</th>
                </tr>
                </tfoot>
              </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
        </div>
</section>

 <section class="content col-sm-12">
				<div class="box box-default">
				  <div class="box-header with-border">
					<h3 class="box-title">Managers</h3>
					<a href="<?=base_url('admin/managers/create');?>" class="btn btn-info btn-flat pull-right" style="margin-left:5px">Create New</a>
					<a href="<?=base_url('admin/managers/upload-firmware');?>" class="btn btn-info btn-flat pull-right" style="margin-left:5px" >Upload Firmware</a>
					<a href="<?=base_url('admin/invite/customreportsettings');?>" class="btn btn-success btn-flat pull-righ" style="margin-left:5px;" title="Delete Never assigned badges">Shift Change Time</a>
					<button type="button" style="margin-left:5px" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal">
						Add/Update Domain
					</button>
				  </div>
				  <div class="box-body">
					  <table id="example1" class="table table-bordered table-striped">
						<thead>
						<tr>
						  <th>#</th>
						  <th>First Name</th>
						  <th>Last Name</th>
						  <th>Email</th>
						  <th>Facility Name</th>
						  <th>FW description</th>
						  <th>FW upload date</th>
						  <th>Actions</th>
						</tr>
						</thead>
						<tbody>
						  <?php 
							$i=1;
							if(!empty($managers)):
						  foreach($managers as $manager): ?> 
						  <tr>
							<td><?php echo $i; $i++;?></td>
							<td><?php echo $manager->vFirstName;?></td>
							<td><?php echo $manager->vLastName;?></td>
							<td><?php echo $manager->vEmail;?></td>
							<td><?php echo $manager->vFacility;?></td>
							<td><?php echo $manager->firmware_details;?></td>
							<td><?php echo $manager->firmware_date;?></td>
							<td>
								<a href="<?=base_url('admin/managers/editManager/'.$manager->vId);?>" class="btn btn-flat btn-sm btn-info"><i class="fa fa-edit"></i></a>
								<a onclick="return confirm('Are you sure you want to delete this Manager?');" href="<?=base_url('admin/managers/deleteManager/'.$manager->vId);?>" class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash"></i></a>
								<?php if($manager->firmware_file != NULL && $manager->firmware_file != "" && file_exists(FCPATH.'uploads/facility_bin/'.$manager->firmware_file)) { ?>
								<a href="<?=base_url('uploads/facility_bin/'.$manager->firmware_file);?>" class="btn btn-flat btn-sm btn-success"><i class="fa fa-download"></i></a>
						  		<?php } ?>
								
								<!-- <a href="<?php //base_url('admin/managers/reportwindow/'.$manager->vId);?>" class="btn btn-flat btn-sm btn-success" title="Add/Edit report window"><i class="fa fa-square"></i></a> -->
						  		
							</td>
						  </tr>
						  <?php endforeach;?>
						  <?php endif;?>
						
						</tbody>
						<tfoot>
						<tr>
						  <th>#</th>
						  <th>First Name</th>
						  <th>Last Name</th>
						  <th>Email</th>
						  <th>Facility Name</th>
						  <th>FW description</th>
						  <th>FW upload date</th>
						  <th>Actions</th>
						</tr>
						</tfoot>
					  </table>
					  <div class="box-footer">
				
				<a href="<?=base_url('admin/invite');?>" class="btn btn-warning pull-right">Cancel</a>
			   </div>  
				  </div>
				  <!-- /.box-body -->
				</div>
				<!-- /.box -->

	</section>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add/Update Domain Name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  <form action="invite/domainupdate" method="POST">
      <div class="modal-body">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Domain:</label>
            <input value="<?= $domain ?>" placeholder="Enter valid domain name" required pattern="[0-9a-zA-Z_.-]*" name="domain" type="text" class="form-control" title="Please enter valid domain name" id="recipient-name">
          </div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary">Add/Update</button>
		</div>
	</form>
    </div>
  </div>
</div>

