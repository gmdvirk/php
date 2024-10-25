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
                        <th>Event ID</th>
                        <th>B Serial</th>
                        <th>R Serial</th>
                        <th>Event Time</th>
                        <th>Upload Time</th>
                        <th data-orderable="false">Ignore</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;
                    if(!empty($events)):
                    foreach($events as $event): ?> 
                        <tr>
                            <td data-orderable="false"><?php echo $i; $i++;?></td>
                            <td><?php echo $event->iId;?></td>
                            <td><?php echo $event->vBadge_serial;?></td>
                            <td><?php echo $event->vReader_serial;?></td>
                            <td><?php echo $event->dCreated_at;?></td>
                            <td><?php echo $event->dUpdated_at;?></td>
                            <?php $checked = $event->ignored > 0 ? 'checked' : '';?>
                            <td data-orderable="false">
                                <input type="checkbox" class="form-check-input"  <?= $checked ?> onclick="ingnoreDeleEvent(this)" id="<?= $event->iId ?>" >
                            </td>
                        </tr> 
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Event ID</th>
                        <th>B Serial</th>
                        <th>R Serial</th>
                        <th>Event Time</th>
                        <th>Upload Time</th>
                        <th>Ignore</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a href="<?=base_url('admin/events/bulkDelete/edate');?>" class="btn btn-warning">Cancel</a>
            <a href="<?=base_url('admin/events/deleteevents');?>" class="btn btn-danger pull-right">Delete Events</a>
        </div>
    </div>
    <!-- /.box -->
</section>