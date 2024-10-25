 <!-- Main content -->
<section class="content">
    <?php if(!empty($this->session->flashdata('success'))) : ?>
        <div class="alert alert-success alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <p><?=$this->session->flashdata('success');?>
        </div>
    <?php endif; ?>
    <?php if(!empty($this->session->flashdata('error'))) : ?>
        <div class="alert alert-error alert-dismissible" >
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Error!</h4>
            <p><?=$this->session->flashdata('error');?>
        </div>
    <?php endif; ?>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $pageTitle; ?></h3>
            <div class="manage-drop"></div>
            <a href="<?=base_url('admin/gateway/create');?>" class="btn btn-info btn-flat pull-right" >Create New</a>
        </div>
        <div class="box-body">
            <table id="gateways-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th data-orderable="false">#</th>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th data-orderable="false">latitude</th>
                        <th data-orderable="false">longitude</th>
                        <th data-orderable="false">Actions</th>
                    </tr>
                </thead>
                <tbody> </tbody>
                <tfoot>
                    <tr>
                        <th data-orderable="false">#</th>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th data-orderable="false">latitude</th>
                        <th data-orderable="false">longitude</th>
                        <th data-orderable="false">Actions</th>
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
    fetchGateways();
  };
</script>