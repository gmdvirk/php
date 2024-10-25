<!-- Main content -->
<section class="content col-sm-6">
	
	<?php if(!empty($this->session->flashdata('error'))) : ?>
        <div class="alert alert-error alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <p><?=$this->session->flashdata('error');?>
        </div>
      <?php endif; ?>
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?=$pageTitle;?></h3>
		</div>
		<div class="box-body">
			<?php 
				$vSerial=set_value('vSerial');
				//~ $vRoom=set_value('vRoom');
				$vAssigned_Facility_ID=set_value('vAssigned_facility');
				$vDescription=set_value('vDescription');
				// $vCustom_config=set_value('vCustom_config');
				if(!empty($devices))
				{
					$vSerial=$devices->vSerial;
					//~ $vRoom=$customer->vRoom;
					$vAssigned_Facility_ID=$devices->vAssigned_Facility_ID;
					$vDescription=$devices->vDescription;
					// $vCustom_config=$devices->vCustom_config;
				}
				if(isset($this->session->editedDeviceDetails)){
					$editedDevice = $this->session->editedDeviceDetails;
					$this->session->unset_userdata('editedDeviceDetails');
					$vSerial = $editedDevice['vSerial'];
					$vAssigned_Facility_ID = $editedDevice['vAssigned_Facility_ID'];
					// $vCustom_config = $devices->vCustom_config;
				}
				echo form_open($action); ?>
			<div class="box-body">
				<div class="form-group">
					<label for="vSerial">Badge Serial</label>
					<input type="text" class="form-control" id="vSerial" value="<?=$vSerial?>" name="vSerial" required />
				</div>
<!--
				<div class="form-group">
					<label for="vSerial">Room</label>
					<input type="text" class="form-control" id="vRoom" value="<?=$vRoom?>" name="vRoom" required />
				</div>
-->
				<div class="form-group">
					<label for="vFacility">Assigned Facility</label>
					<select id="vAssigned_Facility_ID" name="vAssigned_Facility_ID" class="form-control" required >
					<option value="">-Select Facility-</option>
					<?php
					$selected = "";
					foreach($managers as $manager){
						if($manager->vId == $vAssigned_Facility_ID){
							$selected = "selected";
						}else{
							$selected = "";
						}
						echo "<option value='".$manager->vId."' ".$selected.">".$manager->vFacility."</option>";
					}
					?>
					</select>
				</div>
				
				<div class="form-group">
					<label for="vFacility">Assigned User</label>
					<select id="vAssigned_User_ID" name="vAssigned_User_ID" class="form-control">
						<option value="">-Select User-</option>
						<?php
							$selected = "";
							foreach($customers as $customer){
								if($devices->vSerial == $customer->vSerial){
									$selected = "selected";
								}else{
									$selected = "";
								}
								echo "<option value='".$customer->iId."' ".$selected.">".$customer->vFirstName." ".$customer->vLastName."</option>";
							}
						?>
					</select>
				</div>
				
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url('admin/devices');?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
