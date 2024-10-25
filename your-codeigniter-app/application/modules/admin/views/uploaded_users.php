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
            <table id="uploaded_users_table" class="table table-bordered table-striped">
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
                <?php //$i=1;
                   // if(!empty($uploaded_users)):
                   // foreach($uploaded_users as $customer): ?> 
                        <!-- <tr>
                            <td><?php// echo $i; $i++;?></td>
                            <td><?php //echo $customer->firstname;?></td>
                            <td><?php //echo $customer->lastname;?></td>
                            <td><?php// echo $customer->role;?></td>
                            <td><?php //echo $customer->unit;?></td>
                        </tr> -->
                    <?php// endforeach;?>
                <?php// endif;?>                
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
            <?php 
            if($upload['is_processed'] == '1'){?>
                <a class="btn btn-warning pull-right">Register Customers</a>
            <?php }else{ ?>
			    <a href="<?=base_url('admin/customers/registerusers/'.$upload['id']);?>" class="btn btn-warning pull-right">Register Customers</a>
            <?php } ?>
        </div>
    </div>
    <!-- /.box -->
</section>
