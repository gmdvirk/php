<!-- Main content -->
<section class="content col-sm-6">
	
	  <?php if(!empty($this->session->flashdata('error'))) : ?>
        <div class="alert alert-error alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Error!</h4>
            <p><?=$this->session->flashdata('error');?>
        </div>
	  <?php endif; ?>
	  <?php if(!empty($this->session->flashdata('success'))) : ?>
        <div class="alert alert-success alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <p><?=$this->session->flashdata('success');?>
        </div>
      <?php endif; ?>
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?=$pageTitle;?></h3>
		</div>
		<div class="box-body">
			<div class="box-body">
	  	<?php echo form_open_multipart($action); ?>
			<div class="form-group">
					<label for="vFacility">Facility Name</label>
					<select name="facility" id="vFacility" class="form-control" required >
					<!-- <option value="">-Select Facility Name-</option> -->
					<?php
					$selected = "";
					foreach($managers as $facility){
						if($facility->vId == $selected_facility){
							$selected = "selected";
						}else{
							$selected = "";
						}
						echo "<option value='".$facility->vId."' ".$selected.">".$facility->vFacility."</option>";
					}
					?>
					</select>
				</div>
				<div class="form-group">
					<label for="firmWareDetails">Firmware Details</label>
					<input type="text" class="form-control" id="firmwareDetails" value="<?php echo isset($firmware_details) ? $firmware_details : "" ; ?>"  name="firmwareDetails" />
				</div>
				<div class="form-group">
					<label for="firmwareFile"> Firmware File</label>
					<input type="file" class="form-control" id="firmwareFile"  name="firmwareFile" required />
				</div>
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

