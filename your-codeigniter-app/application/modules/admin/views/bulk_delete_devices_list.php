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
        <h4><i class="icon fa fa-check"></i> Success!</h4>
        <p><?=$this->session->flashdata('error');?>
    </div>
    <?php endif; ?>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?=$pageTitle;?></h3>
        </div>
        <div class="box-body">
            <table  id="tobodeleted" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Serial</th>
                        <th>Facility ID</th>
                        <th>Created At</th>
                        <th>Ignore</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;
                    if(!empty($devices)):
                    foreach($devices as $device): ?> 
                        <tr>
                            <td data-orderable="false"><?php echo $i; $i++;?></td>
                            <td><?php echo $device->vSerial;?></td>
                            <td><?php echo $device->vAssigned_Facility_ID;?></td>
                            <td><?php echo $device->dCreated_at;?></td>
                            <?php $checked = $device->ignored > 0 ? 'checked' : '';?>
                            <td data-orderable="false">
                                <input type="checkbox" class="form-check-input"  <?= $checked ?> onclick="ingnoreDeleDevice(this)" id="<?= $device->iId ?>" >
                            </td>
                        </tr> 
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Serial</th>
                        <th>Facility ID</th>
                        <th>Created At</th>
                        <th>Ignore</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a href="<?=base_url('admin/devices/deletedevices');?>" class="btn btn-warning pull-right">Delete Devices</a>
        </div>
    </div>
    <!-- /.box -->
</section>