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
				//~ $eGender=set_value('eGender');
				$vRole=set_value('vRole');
				$vUnit=set_value('vUnit');
				//~ $vEmail=set_value('vEmail');
				//~ $vLocation=set_value('vLocation');
				$vSerial=set_value('vAssigned_Facility_ID');
				$vStart=set_value('vStart');
				$vEnd=set_value('vEnd');
				//~ $vAssigned_Device_ID=set_value('vAssigned_Device_ID');
				//~ $pass_required = "required";
				if(!empty($customer))
				  {
				      $vFirstName=$customer->vFirstName;
					  $vLastName=$customer->vLastName;
					  $vEmail=$customer->vEmail;
				      $vRole=$customer->vRole;
				      $vUnit=$customer->vUnit;
				      //~ $eGender=$customer->eGender;
				      //~ $vEmail=$customer->vEmail;
				      //~ $vLocation=$customer->vLocation;
				      //~ $vAssigned_Facility_ID=$customer->vAssigned_Facility_ID;
				      $vSerial=$customer->vSerial;
				      $vStart=$customer->vStart;
				      $vEnd=$customer->vEnd;
				      //~ $vEmail=$customer->vEmail;
				      //~ $pass_required = "";
				  }
				  if(isset($this->session->editedCustDetails)){
					$editedCustomer = $this->session->editedCustDetails;
					$this->session->unset_userdata('editedCustDetails');
					$vFirstName=$editedCustomer['vFirstName'];
					$vLastName=$editedCustomer['vLastName'];
					$vEmail=$editedCustomer['vEmail'];
					$vRole=$editedCustomer['vRole'];
					$vUnit=$editedCustomer['vUnit'];
					$vSerial=$editedCustomer['vSerial'];
					$vStart=$editedCustomer['vStart'];
					$vEnd=$editedCustomer['vEnd'];
				  }
				echo form_open($action); ?>
			<div class="box-body">
				<div class="form-group">
					<label for="vFirstName">First name</label>
					<input type="text" class="form-control" id="vFirstName" value="<?=$vFirstName?>" name="vFirstName" required />
				</div>
				<div class="form-group">
					<label for="vLastName">Last name</label>
					<input type="text" class="form-control" id="vLastName" value="<?=$vLastName?>" name="vLastName" required />
				</div>
				<div class="form-group">
					<label for="vEmail">Email</label>
					<input type="email" class="form-control" id="vEmail" value="<?=$vEmail?>" name="vEmail"/>
				</div>
				<div class="form-group">
					<label for="vRole">Role</label>
					<input type="text" class="form-control" id="vRole" value="<?=$vRole?>" name="vRole" required />
				</div>
				<div class="form-group">
					<label for="vRole">Unit</label>
					<input type="text" class="form-control" id="vUnit" value="<?=$vUnit?>" name="vUnit" required />
				</div>
<!--
				<div class="form-group">
					<label for="vLocation">Location</label>
					<input type="text" class="form-control" id="vLocation" value="<?#=$vLocation?>" name="vLocation" />
				</div>
				<div class="form-group">
					<label for="vAssigned_Facility_ID">Assigned Facility ID</label>
					<input type="text" class="form-control" id="vAssigned_Facility_ID" value="<?#=$vAssigned_Facility_ID?>" name="vAssigned_Facility_ID" />
				</div>
	-->		<div class="form-group">
					<label for="vAssigned_Device_ID">Assigned Badge Serial</label>
					<select name="vSerial" class="form-control">
					<option value="unassign"> -No Badge Serial- </option>
					<?php
					$selected = "";
					foreach($devices as $device){
						if($device->vSerial == $vSerial){
							$selected = "selected";
						}else{
							$selected = "";
						}
						echo "<option value='".$device->vSerial."' ".$selected.">".$device->vSerial."</option>";
					}
					?>
					</select>
				</div>
				
				<div class="form-group">
					<label for="vRole">Start From</label>
					<input type="date" class="form-control1" id="vStart" value="<?=$vStart?>" name="vStart" required />
				   
					<label for="vRole">End</label>
					<input type="date" class="form-control1" id="vEnd" value="<?=$vEnd?>" name="vEnd" />
				</div>
				
	<!--			<div class="form-group">
					<label for="eGender">Gender</label>
					<select name="eGender" id="eGender" class="form-control">
						<option value="">---Select Gender---</option>
						<option value="M" <?php #if($eGender=='M'){ echo "selected"; }?> >Male</option>
						<option value="F" <?php #if($eGender=='F'){ echo "selected"; }?>>Female</option>
					</select>
				</div>
				<div class="form-group">
					<label for="vEmail">Email / Username</label>
					<input type="email" class="form-control" value="<?#=$vEmail?>" id="vEmail" name="vEmail" required />
				</div>
				<div class="form-group">
					<label for="vPassword">Password</label><?php #if(!empty($customer)) { ?><small class="error">(Leave blank if do not wish to change)</small><?php #}?>
					<input type="text" class="form-control"  id="vPassword" name="vPassword" <?php #echo $pass_required; ?> />
				</div>
			</div>
-->
			<!-- /.box-body -->
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url('managers/customers');?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
