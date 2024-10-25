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
				$gateway_id = set_value('gateway_id');
				$name = set_value('name');
				$description = set_value('description');
				$latitude = set_value('latitude');
				$longitude = set_value('longitude');
				if(!empty($gateway))
				{
					$gateway_id=$gateway->gateway_id;
					$name=$gateway->name;
					$description=$gateway->description;
					$latitude = $gateway->latitude;
					$longitude = $gateway->longitude;
				}
				echo form_open($action); ?>
			<div class="box-body">
			<div class="form-group">
					<label for="id">Id</label>
					<input type="text" class="form-control" id="gateway_id" value="<?=$gateway_id?>" name="gateway_id" required />
				</div>
				<div class="form-group">
					<label for="vReader">Name</label>
					<input type="text" class="form-control" pattern="^[a-zA-Z0-9-]*$" id="name" value="<?=$name?>" name="name" required />
				</div>
				<div class="form-group">
					<label for="description">Description</label>
					<textarea class="form-control" id="description" name="description" required /><?=$description?></textarea>
				</div>
				<div class="form-group">
					<label for="vReader">Latitude</label>
					<input type="text" pattern="^\d+\.{0,1}\d{0,8}$" class="form-control" id="latitude" value="<?=$latitude?>" name="latitude" required />
				</div>
				<div class="form-group">
					<label for="vReader">Longitude</label>
					<input type="text" pattern="^\d+\.{0,1}\d{0,8}$" class="form-control" id="longitude" value="<?=$longitude?>" name="longitude" required />
				</div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url('admin/gateway	');?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
