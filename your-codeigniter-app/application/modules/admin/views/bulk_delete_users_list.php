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
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Unit</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Ignore</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;
                    if(!empty($users)):
                    foreach($users as $customer): ?> 
                        <tr>
                            <td data-orderable="false"><?php echo $i; $i++;?></td>
                            <td><?php echo $customer->vFirstName;?></td>
                            <td><?php echo $customer->vLastName;?></td>
                            <td><?php echo $customer->vRole;?></td>
                            <td><?php echo $customer->vUnit;?></td>
                            <td><?php echo $customer->vStart;?></td>
                            <td><?php echo $customer->vEnd;?></td>
                            <?php $checked = $customer->ignored > 0 ? 'checked' : '';?>
                            <td data-orderable="false">
                                <input type="checkbox" class="form-check-input"  <?= $checked ?> onclick="ingnoreDeleUser(this)" id="<?= $customer->iId ?>" >
                            </td>
                        </tr> 
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Unit</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Ignore</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a href="<?=base_url('admin/customers/deleteusers');?>" class="btn btn-warning pull-right">Delete Customers</a>
        </div>
    </div>
    <!-- /.box -->
</section>