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
				$unitName=set_value('name');
				if(!empty($unit))
				{
					$unitName = $unit->name;
				}
				echo form_open($action); ?>
			<div class="box-body">
				<div class="form-group">
					<label for="vReader">Unit Name</label>
					<input type="text" class="form-control" id="unitName" value="<?= $unitName ?>" name="unitName" required />
				</div>
			</div>
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url('admin/units');?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
