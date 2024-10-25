 <!-- Main content -->
      <section class="content col-sm-12">
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
              <select name="vFacilityFilter" id="vFacilityFilter" class="custom-select custom-select-lg mb-3">
                <option <?= $this->session->customerFacility== 'ALL' ? 'selected' : '' ; ?> value="ALL">ALL</option>
                <option <?= $this->session->customerFacility== 'NONE' ? 'selected' : '' ; ?> value="NONE">NONE</option>
                <?php if(!empty($managers)):
                  foreach($managers as $manager): ?>
                      <option <?= $this->session->customerFacility == $manager->vId ? 'selected' : '' ; ?> value="<?= $manager->vId; ?>"><?php echo $manager->vFacility;?></option>
                  <?php endforeach;?>
                <?php endif;?>
              </select>
              <span>
                <label class="" for="Hide Unassigned Users">Hide Unassigned Users</label>
                <input class="" type="checkbox" id="hideUnassignedUsers" onChange="fetchCustomers(this.checked)">
              </span>
            </div>
            <a href="<?=base_url('admin/customers/download');?>" class="btn btn-success btn-flat pull-right" style="margin-right:5px">Download CSV</a>
            <a href="<?=base_url('admin/customers/bulkDelete/role');?>" class="btn btn-danger btn-flat pull-right" style="margin-right:5px">Bulk Delete</a>
            <a href="<?=base_url('admin/customers/upload');?>" class="btn btn-warning btn-flat pull-right" style="margin-right:5px">Upload Users</a>
            <a href="<?=base_url('admin/customers/create');?>" class="btn btn-info btn-flat pull-right" >Create New</a>
          </div>
          <div class="box-body">
              <table id="customer-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th data-orderable="false">#</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Unit</th>
                  <th>Facility Name</th>
                  <th>Badge Serial</th>
                  <th data-orderable="false">Actions</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Unit</th>
                  <th>Facility Name</th>
                  <th>Badeg Serial</th>
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
    fetchCustomers();
  };
</script>