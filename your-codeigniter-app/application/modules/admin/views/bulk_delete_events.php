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
                        <label for="bulkDeleteBy">Delete By</label>
                        <select name="bulkDeleteBy" id="bulkDeleteBy" class="form-control" required>
                            <option <?= $type == 'edate' ? 'selected' : '' ?> value="edate">Event DATE</option>
                            <option <?= $type == 'udate' ? 'selected' : '' ?> value="udate">Upload DATE</option>
                            <option <?= $type == 'rserial' ? 'selected' : '' ?> value="rserial?type=1">Beacon Serial</option>
                            <option <?= $type == 'bserial' ? 'selected' : '' ?> value="bserial?type=1">Badge Serial</option>
                        </select>
                    </div>
                    <?php if($type == 'edate') {?>
                    <div class="form-group"  id="addedDates">
					    <label for="eDateFrom"> From</label>
					    <input type="date" class="form-control1" id="vStart" value="<?=  date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) )?>" name="vStart" required />
				   
					    <label for="vRole"> To</label>
					    <input type="date" class="form-control1" id="vEnd" value="<?=Date('Y-m-d')?>" name="vEnd" />
				    </div>
                    <?php }else if($type == 'udate'){ ?>
                        <div class="form-group"  id="addedDates">
					    <label for="vRole"> From</label>
					    <input type="date" class="form-control1" id="vStart" value="<?=  date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) )?>" name="vStart" required />
				   
					    <label for="vRole"> To</label>
					    <input type="date" class="form-control1" id="vEnd" value="<?=Date('Y-m-d')?>" name="vEnd" />
				    </div>
                    <?php }else if($type == 'rserial'){ ?>
                        <div class="form-group">
                            <select name="rserialType" id="rserialType" class="form-control" required>
                                <option <?= $_GET['type'] == '1' ? 'selected' : '' ?> value="rserial?type=1">Single</option>
                                <option <?= $_GET['type'] == '2' ? 'selected' : '' ?> value="rserial?type=2">Range</option>
                            </select>
                        </div>
                        <?php }else if($type == 'bserial'){ ?>
                            <div class="form-group">
                            <select name="bserialType" id="bserialType" class="form-control" required>
                                <option <?= $_GET['type'] == '1' ? 'selected' : '' ?> value="bserial?type=1">Single</option>
                                <option <?= $_GET['type'] == '2' ? 'selected' : '' ?> value="bserial?type=2">Range</option>
                            </select>
                        </div>
                        <?php } ?>
                        <?php if($type == 'rserial' || $type == 'bserial'){ ?>
                        <?php if($_GET['type'] == '1') {?>
                        <div class="form-group">
                            <label for="bulkDeleteBy">Delete Serial</label>
                            <input type="number" name="rSerial" required>
                        </div>
                        <?php  }else{ ?>
                        <div class="form-group"  id="addedDates">
                            <label for="vRole"> From</label>
                            <input type="number" class="form-control" id="vStart" name="vStart" required />
                    
                            <label for="vRole"> To</label>
                            <input type="number" class="form-control" id="vEnd" name="vEnd" required />
				        </div>
                        <?php } ?>
                    <?php } ?>
                </div>
			    <!-- /.box-body -->
		    </div>
		    <!-- /.box-body -->
		    <div class="box-footer">
			    <button type="submit" class="btn btn-danger">Delete</button>
                <a href="<?=base_url('admin/events');?>" class="btn btn-info btn-flat pull-right" >Cancel</a>
		    </div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
<script>

</script>