<!-- Main content -->
<?php 
if($manager) {
    $w1Start = $manager->w1Start;
    $w1End = $manager->w1End;
    $w2Start = $manager->w2Start;
    $w2End = $manager->w2End;
}else {
    $w1Start = '';
    $w1End = '';
    $w2Start = '';
    $w2End = '';
}


?>

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
        <?php echo form_open($action); ?>
		<div class="box-body">
			<div class="box-body">
            <h4>Notice</h4>
                <ul style="margin-bottom:30px">
                    <li>Start time can't be greater than End.</li>
                    <li>Time difference can't be less then 10 minutes.</li>
                </ul>
                <h4>Window 1</h4>
                <div class="form-group">
                    <label for="vRole">Start From</label>
                   
                    <input type="time" class="form-control1" id="w1Start" value="<?php echo set_value('w1Start')?set_value('w1Start'):$w1Start; ?>" name="w1Start" required />
                    
                    <label for="vRole">End</label>
                    <input type="time" class="form-control1" id="w1End" value="<?php echo set_value('w1End')?set_value('w1End'):$w1End; ?>" name="w1End" />
                </div>
                <h4>Window 2</h4>
                <div class="form-group">
                    <label for="vRole">Start From</label>
                    <input type="time" class="form-control1" id="w2Start" value="<?php echo set_value('w2Start')?set_value('w2Start'):$w2Start; ?>" name="w2Start" required />
                    
                    <label for="vRole">End</label>
                    <input type="time" class="form-control1" id="w2End" value="<?php echo set_value('w2End')?set_value('w2End'):$w2End; ?>" name="w2End" />
                </div>
			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="submit" class="btn btn-success">Save</button>
			<a href="<?=base_url('admin/invite');?>" class="btn btn-warning pull-right">Cancel</a>
		</div>
		</form>
	</div>
	<!-- /.box -->
</section>
<!-- /.content -->
