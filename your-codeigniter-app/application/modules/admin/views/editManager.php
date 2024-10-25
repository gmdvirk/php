<!-- Main content -->
<section class="content col-sm-6">
	<?php if(!empty($this->session->flashdata('error'))) : ?>
        <div class="alert alert-error alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Error!</h4>
            <p><?=$this->session->flashdata('error');?>
        </div>
    <?php endif; ?>
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?=$pageTitle;?></h3>
		</div>
		<div class="box-body">
			<?php 
				$vFirstName=set_value('vFirstName');
				$vLastName=set_value('vLastName');
				$vEmail=set_value('vEmail');
				$vFacility=set_value('vFacility');
				$vJsonString = set_value('vJsonString');
				$pass_required = "required";
				if(!empty($manager))
				  {
				      $vFirstName=$manager->vFirstName;
				      $vLastName=$manager->vLastName;
				      $vEmail=$manager->vEmail;
				      $vFacility=$manager->vFacility;
					  $pass_required = "";
					  $vJsonString= pretty_json($manager->vJsonString);
				  }
				echo form_open($action); ?>
			<div class="box-body">
				<div class="form-group">
					<label for="vFirstName">Full name</label>
					<input type="text" class="form-control" id="vFirstName" value="<?=$vFirstName?>" name="vFirstName" required />
				</div>
				<div class="form-group">
					<label for="vLastName">Last name</label>
					<input type="text" class="form-control" id="vLastName" value="<?=$vLastName?>" name="vLastName" required />
				</div>
				<div class="form-group">
					<label for="vRole">Email</label>
					<input type="email" class="form-control" id="vEmail" value="<?=$vEmail?>" name="vEmail" <?php if($vEmail){echo "readonly";}?> required />
				</div>
				<div class="form-group">
					<label for="vLocation">Facility Name</label>
					<input type="text" class="form-control" id="vFacility" value="<?=$vFacility?>" name="vFacility" />
				</div>

				<div class="form-group">
					<label for="vPassword">Password</label><?php if(!empty($manager)) { ?><small class="error">(Leave blank if do not wish to change)</small><?php }?>
					<input type="text" class="form-control"  id="vPassword" name="vPassword" <?php echo $pass_required; ?> />
				</div>
				<?php if(isset($manager)){ ?>
				<!-- <div class="form-group">
					<label for="jsonString">Desired Badge Settings</label>
					<textarea disabled class="form-control" id="vJsonString" name="vJsonString" rows="5"><?=$vJsonString?></textarea>
				</div> -->
				<a href="<?=base_url('admin/managers/edit-json/'.$manager->vId);?>" class="btn btn-info pull-left">View and Edit Badge Settings</a>
				<?php } ?>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url('admin/invite');?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
