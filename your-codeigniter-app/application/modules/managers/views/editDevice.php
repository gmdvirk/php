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
				$vSerial=set_value('vSerial');
				
				//~ $vAssigned_facility=set_value('vAssigned_facility');
				//~ $vType=set_value('vType');
				//~ $vDescription=set_value('vDescription');
				//~ $vCustom_config=set_value('vCustom_config');
				if(!empty($devices))
				  {
				      $vSerial=$devices->vSerial;
				      
				      //~ $vAssigned_facility=$devices->vAssigned_facility;
				      //~ $vType=$devices->vType;
				      //~ $vDescription=$devices->vDescription;
				      //~ $vCustom_config=$devices->vCustom_config;
				  }
				echo form_open($action); ?>
			<div class="box-body">
				<div class="form-group">
					<label for="vSerial">Badge Serial</label>
					<input type="text" class="form-control" id="vSerial" value="<?=$vSerial?>" name="vSerial" required />
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
<!--
				<div class="form-group">
					<label for="vAssigned_facility">Assigned Facility</label>
					<input type="text" class="form-control" id="vAssigned_facility" value="<?#=$vAssigned_facility?>" name="vAssigned_facility" />
				</div>
				<div class="form-group">
					<label for="vType">Type</label>
					<input type="text" class="form-control" id="vType" value="<?#=$vType?>" name="vType" />
				</div>
				<div class="form-group">
					<label for="vDescription">Description</label>
					<input type="text" class="form-control" id="vDescription" value="<?#=$vDescription?>" name="vDescription" />
				</div>
				<div class="form-group">
					<label for="vCustom_config">Custom Config</label>
					<input type="text" class="form-control" id="vCustom_config" value="<?#=$vCustom_config?>" name="vCustom_config" />
				</div>
-->
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url('managers/devices');?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
