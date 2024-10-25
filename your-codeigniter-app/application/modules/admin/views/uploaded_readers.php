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
            <table id="uploaded_readers_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Beacons Serial</th>
                        <th>Room Number</th>
                        <th>Unit</th>
                        <th>Description</th>
                        <th>Ignore</th>
                    </tr>
                </thead>
                <tbody>      
                </tbody>
                <tfoot>
                <tr>
                        <th>#</th>
                        <th>Beacons Serial</th>
                        <th>Room Number</th>
                        <th>Unit</th>
                        <th>Description</th>
                        <th>Ignore</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?php if($upload['is_processed'] == 1){?>
                <button disabled class="btn btn-warning pull-right">Register Beacons</button>
            <?php }else{?>
                <a href="<?=base_url('admin/beacons/registerreaders/'.$upload['id']);?>" class="btn btn-warning pull-right">Register Beacons</a>
            <?php } ?>
            <a href="<?=base_url('admin/beacons')?>" class="btn btn-success">Go To Beacons</a>
		</div>
    </div>
    <!-- /.box -->
</section>
