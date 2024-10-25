<!-- Main content -->
<section class="content col-sm-6">	
	<?php if(!empty($this->session->flashdata('error'))) : ?>
    <div class="alert alert-error alert-dismissible" >
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Error!</h4>
        <p><?=$this->session->flashdata('error');?>
    </div>
    <?php elseif(!empty($this->session->flashdata('success'))): ?>
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
			<?php echo form_open_multipart($action); ?>
			    <div class="box-body">
                    <div class="form-group">
                        <label for="csv-file">Unit CSV</label>
                        <input type="file" class="form-control-file" id="csv" name="csv_file">
                    </div>
                </div>
		    </div>
		    <div class="box-footer">
			    <button type="submit" class="btn btn-success">Upload</button>
                <a href="<?=base_url('admin/units');?>" class="btn btn-warning btn-flat pull-right" style="margin-right:5px">Cancel</a>
		    </div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->

