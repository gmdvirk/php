<!-- Main content -->
<section class="content col-sm-12">
	
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
			<?php 
				$vFirstName=set_value('vFirstName');
				$vLastName=set_value('vLastName');
				$vEmail=set_value('vEmail');
				$eGender=set_value('eGender');
				$vRole=set_value('vRole');
				$vUnit=set_value('vUnit');
				$vFacility=set_value('vFacility');
				$vAssigned_Facility_ID=set_value('vAssigned_Facility_ID');
				$vSerial=set_value('vSerial');
				$vStart=set_value('vStart');
				$vEnd=set_value('vEnd');
				$vJsonString=set_value('vJsonString');
				$jsonAccepted=set_value('jsonAccepted');
				$pass_required = "required";
				if(!empty($customer))
				  {
				      $vFirstName=$customer->vFirstName;
					  $vLastName=$customer->vLastName;
					  $vEmail=$customer->vEmail;
				      $eGender=$customer->eGender;
				      $vRole=$customer->vRole;
				      $vUnit=$customer->vUnit;
				      
				      $vFacility=$customer->vAssigned_Facility_ID;
				      $vAssigned_Facility_ID=$customer->vAssigned_Facility_ID;
				      $vSerial=$customer->vSerial;
				      $vStart=$customer->vStart;
					  $vEnd=$customer->vEnd;
					  $vJsonString= pretty_json($customer->vJsonString);
					  $jsonAccepted= pretty_json($customer->jsonAccepted);
				      $pass_required = "";
				  }
				  if(isset($this->session->editedCustDetails)){
						$editedCustomer = $this->session->editedCustDetails;
						$this->session->unset_userdata('editedCustDetails');
						$vFirstName = $editedCustomer['vFirstName'];
						$vLastName = $editedCustomer['vLastName'];
						$vEmail = $editedCustomer['vEmail'];
						$vRole = $editedCustomer['vRole'];
						$vUnit = $editedCustomer['vUnit'];
						$vFacility = $editedCustomer['vAssigned_Facility_ID'];
						$vSerial = $editedCustomer['vSerial'];
						$vStart = $editedCustomer['vStart'];
						$vEnd = $editedCustomer['vEnd'];
				  }
				echo form_open($action); ?>
			<input type="hidden" id="vAssigned_vSerial" value="<?= $vSerial ?>">
			<div class="box-body">
				<div class="form-group col-sm-6">
					<label for="vFirstName">First name</label>
					<input type="text" class="form-control" id="vFirstName" value="<?=$vFirstName?>" name="vFirstName" required />
				</div>
				<div class="form-group col-sm-6">
					<label for="vLastName">Last name</label>
					<input type="text" class="form-control" id="vLastName" value="<?=$vLastName?>" name="vLastName" required />
				</div>
				<div class="form-group col-sm-12">
					<label for="vEmail">Email</label>
					<input type="email" class="form-control" id="vEmail" value="<?=$vEmail?>" name="vEmail" />
				</div>
				<div class="form-group col-sm-6">
					<label for="vRole">Role</label>
					<select name="vRole" id="vRole" class="form-control" >
					<option value="">-Select Role-</option>
					<?php
						$selected = "";
						foreach($roles as $role){
							if($role->name == $vRole){
								$selected = "selected";
							}else{
								$selected = "";
							}
							echo "<option value='".$role->name."' ".$selected.">".$role->name."</option>";
						}
					?>
					</select>
				</div>

				<div class="form-group col-sm-6">
					<label for="vUnit">Unit</label>
					<select name="vUnit" id="vUnit" class="form-control" >
					<option value="">-Select Unit-</option>
					<?php
						$selected = "";
					foreach($units as $unit){
						if($unit->name == $vUnit){
							$selected = "selected";
						}else{
							$selected = "";
						}
						echo "<option value='".$unit->name."' ".$selected.">".$unit->name."</option>";
					}
					?>
					</select>
				</div>

				<div class="form-group col-sm-6">
					<label for="vFacility">Facility Name</label>
					<select name="vAssigned_Facility_ID" id="vFacility" class="form-control" required >
					<option value="">-Select Facility Name-</option>
					<?php
					$selected = "";
					foreach($managers as $facility){
						if($facility->vId == $vFacility){
							$selected = "selected";
						}else{
							$selected = "";
						}
						echo "<option value='".$facility->vId."' ".$selected.">".$facility->vFacility."</option>";
					}
					?>
					</select>
				</div>
					
				<div class="form-group col-sm-6">
					<label for="vAssigned_Device_ID">Assigned Badge Serial</label>
					<select name="vSerial" id="Badge" class="form-control" ></select>
				</div>
				
				<div class="form-group col-sm-6">
					<label for="vRole">Start From</label>
					<input type="date" class="form-control1" id="vStart" value="<?=$vStart?>" name="vStart" required />
				   
					<label for="vRole">End</label>
					<input type="date" class="form-control1" id="vEnd" value="<?=$vEnd?>" name="vEnd" />
				</div>
				<?php if(isset($customer)){ ?>
				<a href="<?=base_url('admin/customers/edit-json/'.$customer->iId);?>" class="btn btn-info pull-left">View and Edit Badge Settings</a>
				<?php } ?>
			</div>
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url('admin/customers');?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
</section>

