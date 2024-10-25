<!-- Main content -->
<section class="content">
  <?php if(!empty($this->session->flashdata('success'))) : ?>
  <div class="alert alert-success alert-dismissible" >
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <p><?=$this->session->flashdata('success');?>
  </div>
<?php endif; ?>
          
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title"><?=$pageTitle;?></h3>
      <div class="manage-drop">
        <label>Facilities</label>
        <select name="vFacilityFilterDevice" id="vFacilityFilterDevice" class="custom-select custom-select-lg mb-3">
          <option <?= $this->session->deviceFacility== 'ALL' ? 'selected' : '' ; ?> value="ALL">ALL</option>
          <option <?= $this->session->deviceFacility== 'NONE' ? 'selected' : '' ; ?> value="NONE">NONE</option>
          <?php if(!empty($managers)):
            foreach($managers as $manager): ?>
                <option <?= $this->session->deviceFacility == $manager->vId ? 'selected' : '' ; ?> value="<?= $manager->vId; ?>"><?php echo $manager->vFacility;?></option>
            <?php endforeach;?>
          <?php endif;?>
        </select>
      </div>
      <a href="<?=base_url('admin/devices/bulkDelete');?>" class="btn btn-danger btn-flat pull-right" title="Delete Never assigned badges" style="margin-right:5px">Bulk Delete</a>
      <a href="<?=base_url('admin/devices/upload');?>" class="btn btn-warning btn-flat pull-right" style="margin-right:5px">Upload Devices</a>
      <a href="<?=base_url('admin/devices/create');?>" class="btn btn-info btn-flat pull-right" >Create New</a>
    </div>
    <div class="box-body">
        <table id="devices-table" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th data-orderable="false">#</th>
            <th>Badge Serial</th>
            <!-- <th>Type</th> -->
            <th>Assigned Facility</th>
            <th>Created By</th>
            <th data-orderable="false">Actions</th>
          </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
          <tr>
            <th>#</th>
            <th>Badge Serial</th>
            <!-- <th>Type</th> -->
            <th>Assigned Facility</th>
            <th>Created By</th>
            <th>Actions</th>
          </tr>
          </tfoot>
        </table>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</section>
<!-- /.content -->
<script>
  window.onload = function() {
    fetchDevices();
  };
</script>