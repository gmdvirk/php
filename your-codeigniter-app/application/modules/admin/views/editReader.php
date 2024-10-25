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
				$vReaderSerial=set_value('vReaderSerial');
				$vDescription=set_value('vDescription');
				$vFacility=set_value('vFacility');
				$v2=set_value('v2');
				$vUnit=set_value('vUnit');
				$roomNumber=set_value('roomNumber');
				if(!empty($readers))
				{
					$vReaderSerial=$readers->vReaderSerial;
					$vDescription=$readers->vDescription;
					$vFacility = $readers->vFacility;
					$vUnit = $readers->vUnit;
					$v2 = $readers->v2;
					$roomNumber = $readers->roomNumber;
				}
				echo form_open($action); ?>
			<div class="box-body">
				<div class="form-group">
					<label for="vReader">Application Name</label>
					<input type="text" readonly class="form-control" id="applicationName" value="<?=$domain?>" name="applicationName" required />
				</div>
				<div class="form-group">
					<label for="vReader">Beacon Serial</label>
					<input type="text" class="form-control" id="vReaderSerial" value="<?=$vReaderSerial?>" name="vReaderSerial" required />
				</div>
				<div class="form-group">
					<label for="vReader">Room Number</label>
					<input type="text" class="form-control" id="roomNumber" value="<?=$roomNumber?>" name="roomNumber" required />
				</div>
				<div class="form-group">
					<label for="vUnit">Unit</label>
					<select name="vUnit" class="form-control" required >
					<option value="">-Select Unit-</option>
					<?php
					// var_dump($units);exit;
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
				<div class="form-group">
					<label for="vDescription">Description</label>
					<textarea class="form-control" readonly id="vDescription" name="vDescription" required /><?=$vDescription?></textarea>
				</div>
				
				<div class="form-group">
					<label for="vFacility">Assigned Facility</label>
					<select name="vFacility" class="form-control" required >
					<option value="">-Select Facility-</option>
					<?php
					$selected = "";
					foreach($managers as $manager){
						if($manager->vId == $vFacility){
							$selected = "selected";
						}else{
							$selected = "";
						}
						echo "<option value='".$manager->vId."' ".$selected.">".$manager->vFacility."</option>";
					}
					?>
					</select>
				</div>

				<div class="form-group form-check">
					<label style="margin-left: '35px'" class="form-check-label" for="exampleCheck1">V2</label>&nbsp;&nbsp;
					<input type="checkbox" value="1" <?= $v2 == 1 ? 'checked' : '' ?> class="form-check-input" id="v2" name="v2">
				</div>

				<?php if($v2 == 1 && !empty($readers)){ ?>
					<a href="<?=base_url('admin/beacons/editsettings/'.$readers->iId);?>" class="btn btn-info pull-left">View and Edit Reader Settings</a>
				<?php } ?>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url('admin/beacons');?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
