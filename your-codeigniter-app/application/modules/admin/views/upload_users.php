<!-- Main content -->
<style>
    .limitInfo  {
        color: blue
    }
</style>
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
                        <label for="vFacility">Facility Name</label>
                        <select name="vAssigned_Facility_ID" id="vFacility" class="form-control" required >
                            <?php $selected = "";
                                foreach($managers as $facility){
                                    echo "<option value='".$facility->vId."' ".$selected.">".$facility->vFacility."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="csv-file">User's CSV</label>
                        <input type="file" class="form-control-file" id="csv" name="csv_file">
                    </div>
                </div>
			    <!-- /.box-body -->
		    </div>
		    <!-- /.box-body -->
		    <div class="box-footer">
			    <button type="submit" class="btn btn-success">Upload</button>
		    </div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->

