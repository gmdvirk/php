 <!-- Main content -->
      <section class="content col-sm-6">
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
            </div>
            <a href="<?=base_url('managers/devices/create');?>" class="btn btn-info btn-flat pull-right" >Create New</a>
          </div>
          <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Badge Serial</th>
                  
<!--
                  <th>Type</th>
                  <th>Assigned To</th>
-->
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                    $i=1;
                    if(!empty($devices)):
                  foreach($devices as $device): ?> 
                  <tr>
                    <td><?php echo $i; $i++;?></td>
                    <td><?php echo $device->vSerial;?></td>
                   
<!--
                    <td><?php #echo $device->vType;?></td>
                    <td><?php #echo $device->vAssigned_facility;?></td>
-->
                    <td>
                        <a href="<?=base_url('managers/devices/editDevice/'.$device->iId);?>" class="btn btn-flat btn-sm btn-info"><i class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Are you sure you want to delete this device?');" href="<?=base_url('managers/devices/deleteDevice/'.$device->iId);?>" class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                  <?php endforeach;?>
                  <?php endif;?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                  <th>Badge Serial</th>
               
<!--
                  <th>Type</th>
                  <th>Assigned To</th>
-->
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
