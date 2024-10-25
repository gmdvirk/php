<!-- Main content -->
<?php 
if($event_setting) {
    $start = $event_setting['wStart'];
    $end = $event_setting['wEnd'];
    $start2 = $event_setting['w2Start'];
    $end2 = $event_setting['w2End'];
}else {
    $start = '';
    $end = '';
    $start2 = '';
    $end2 = '';
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
                    <li>For min/max - Time is in UTC/GMT.</li>
                </ul>
                <h4>Window 1</h4>
                <div class="form-group">
                    <label for="reportstart">Start From</label>
                    <input type="time" class="form-control1" id="wStart" value="<?php echo set_value('wStart')?set_value('wStart'):$start; ?>" name="wStart" required />
                    
                    <label for="reportend">End</label>
                    <input type="time" class="form-control1" id="wEnd" value="<?php echo set_value('wEnd')?set_value('wEnd'):$end; ?>" name="wEnd" />
                </div>

                <h4>Window 2</h4>
                <div class="form-group">
                    <label for="reportstart">Start From</label>
                    <input type="time" class="form-control1" id="wStart" value="<?php echo set_value('w2Start')?set_value('w2Start'):$start2; ?>" name="w2Start" required />
                    
                    <label for="reportend">End</label>
                    <input type="time" class="form-control1" id="wEnd" value="<?php echo set_value('w2End')?set_value('w2End'):$end2; ?>" name="w2End" />
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
