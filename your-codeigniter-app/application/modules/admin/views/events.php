 <!-- Main content -->
      <section class="content">
        <?php if(!empty($this->session->flashdata('success'))) { ?>
        <div class="alert alert-success alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <p><?=$this->session->flashdata('success');?>
        </div>
      <?php } ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $pageTitle; ?></h3>
			<div class="manage-drop"></div>
			<a href="<?=base_url('admin/events/bulkDelete/edate');?>" class="btn btn-danger btn-flat pull-right" title="Delete Never assigned badges">Bulk Delete</a>
          </div>
          <div class="box-body">
		   <table id="events_" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Event Id</th>
						<th>B Serial</th>
						<th>R Serial</th>
						<th>Event</th>
						<th>Type</th>
						<th>Event time</th>
						<th>Upload time</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Event Id</th>
						<th>B Serial</th>
						<th>R Serial</th>
						<th>Event</th>
						<th>Type</th>
						<th>Event time</th>
						<th>Upload time</th>
					</tr>
				</tfoot>
			 </table>
		</div>
	</div>
</section>
