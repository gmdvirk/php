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
			<h3 class="box-title"><?=$pageTitle;?></h3><br/>
		</div>
        <div class="box-header with-border">
        <p>Note: Will only bulk delete users who have never been assigned a badge before</p>
		</div>
       
		<div class="box-body">
			<?php echo form_open_multipart($action); ?>
			    <div class="box-body">                    
                    <div class="form-group">
                        <label for="bulkDeleteBy">Delete By</label>
                        <select name="bulkDeleteBy" id="bulkDeleteBy" class="form-control" required>
                                   <option <?= $type == 'role' ? 'selected' : '' ?> value="role">Role</option>
                                   <option <?= $type == 'unit' ? 'selected' : '' ?> value="unit">Unit</option>
                                   <option <?= $type == 'date_added' ? 'selected' : '' ?> value="date_added">Start Date</option>
                        </select>
                    </div>
                    <?php if($type == 'date_added') {?>
                    <div class="form-group"  id="addedDates">
					    <label for="vRole">Added From</label>
					    <input type="date" class="form-control1" id="vStart" value="<?=  date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) )?>" name="vStart" required />
				   
					    <label for="vRole">Added To</label>
					    <input type="date" class="form-control1" id="vEnd" value="<?=Date('Y-m-d')?>" name="vEnd" />
				    </div>
                    <?php }else if($type == 'role'){ ?>
                        <div class="form-group">
                        <label for="vRole">Roles</label>
                        <select name="vRole" id="vRole" class="form-control" required >
                            <?php 
                                foreach($data as $d){
                                    $selected = $lastselected == $d->vRole ? 'selected' : "";
                                    echo "<option value='".$d->vRole."' ".$selected.">".$d->vRole."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <?php }else if($type == 'unit'){ ?>
                        <div class="form-group">
                        <label for="vUnit">Units</label>
                        <select name="vUnit" id="vUnit" class="form-control" required >
                            <?php 
                                foreach($data as $d){
                                    $selected = $lastselected == $d->vUnit ? 'selected' : "";
                                    echo "<option value='".$d->vUnit."' ".$selected.">".$d->vUnit."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <?php } ?>
                </div>
			    <!-- /.box-body -->
		    </div>
		    <!-- /.box-body -->
		    <div class="box-footer">
			    <button type="submit" class="btn btn-danger">Delete</button>
                <a href="<?=base_url('admin/customers');?>" class="btn btn-info btn-flat pull-right" >Cancel</a>
		    </div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
<script>

</script>