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
        <h3 class="box-title"><?= $pageTitle; ?></h3>
          <div class="manage-drop">
            <?php if(isset($vSerial)) {?>
              <input type="hidden" name="deviceSerial" id="deviceSerial" value="<?= $vSerial; ?>">
            <?php }elseif (isset($user_id)) {?>
              <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
            <?php } ?>
          </div>
          <?php if(isset($vSerial)) {?>
            <a href="<?=base_url('admin/devices');?>" class="btn btn-info btn-flat pull-right" >Back</a>
          <?php }elseif (isset($user_id)) {?>
            <a href="<?=base_url('admin/customers');?>" class="btn btn-info btn-flat pull-right" >Back</a>
          <?php } ?>
      </div>
      <div class="box-body">
        <table id="history-table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Badge Serial</th>
              <th>Customer Name</th>
              <th>Customer Role</th>
              <th>Assigned From</th>
              <th>Assigned Till</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th>Badge Serial</th>
              <th>Customer Name</th>
              <th>Customer Role</th>
              <th>Assigned From</th>
              <th>Assigned Till</th>
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
    <?php if(isset($vSerial)) {?>
      fetchDeviceHistory();
    <?php }elseif (isset($user_id)) {?>
      fetchCustDeviceHistory();
    <?php } ?>
  };
</script>