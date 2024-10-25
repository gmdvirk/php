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
      

				<div class="box box-default">
				  <div class="box-header with-border">
					<h3 class="box-title">Deleted Managers</h3>
					
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
							<td>
								
							<a onclick="return confirm('Are you sure you want to Restore this Manager?');" href="<?=base_url('admin/managers/restoreManager/'.$manager->vId);?>" class="btn btn-flat btn-sm btn-warning">Restore</a>
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
						  <th>Actions</th>
						</tr>
						</tfoot>
					  </table>
				  </div>
				  <div class="box-footer">
				
				<a href="<?=base_url('admin/invite');?>" class="btn btn-warning pull-right">Cancel</a>
			   </div>  
				  <!-- /.box-body -->
				</div>
				<!-- /.box -->

	</section>
