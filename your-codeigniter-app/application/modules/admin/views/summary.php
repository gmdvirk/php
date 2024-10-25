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
<!--
            <a href="<?=base_url('admin/customers/create');?>" class="btn btn-info btn-flat pull-right" >Create New</a>
-->
		</div>
			<div class="box-body">
				<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Id</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Role</th>
                  <th>Unit</th>
                  <th>Badge Serial</th>
                  <th>Reader Serial</th>
                  <th>Facility Name</th>
                  <th>Reader Desc.</th>
                  <th>Reader Type</th>
                  <th>Compliance</th>
                  <th>Timestamp</th>
                </tr>
                </thead>
				<tbody>
                <?php $i=1;
                 if(!empty($summary)):
                  foreach($summary as $summar): ?> 
                
					<tr>
					<td><?=$i; $i++;?></td>
					<td><?php echo $summar->iId;?></td>
					<td><?php echo $summar->vFirstName;?></td>
					<td><?php echo $summar->vLastName;?></td>
					<td><?php echo $summar->vRole;?></td>
					<td><?php echo $summar->vUnit;?></td>
					<td><?php echo $summar->vBadge_serial;?></td>
					<td><?php echo $summar->vReader_serial;?></td>
					<td><?php echo $summar->vFacility;?></td>
					<td><?php echo $summar->vDescription;?></td>
					<td><?php echo $summar->iEvent_num;?></td>
					<td><?php echo $summar->vEvent_type;?></td>
					<td><?php echo $summar->tTimestamp;?></td>
					</tr>
					<?php endforeach;?>
                  <?php endif;?>
				</tbody>
				<tfoot>
				 <tr>
                  <th>#</th>
                  <th>Id</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Role</th>
                  <th>Unit</th>
                  <th>Badge Serial</th>
                  <th>Reader Serial</th>
                  <th>Facility Name</th>
                  <th>Reader Desc.</th>
                  <th>Reader Type</th>
                  <th>Compliance</th>
                  <th>Timestamp</th>
                </tr>
                </tfoot>
				</table>
			</div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->

<!--
		<td><?php #echo $user->vFirstName;?></td>
					<td><?php #echo $user->vLastName;?></td>
					<td><?php #echo $user->vRole;?></td>
					<td><?php #echo $user->vUnit;?></td>
					<td><?php #if($dSerial=$this->device->getDeviceById($user->vAssigned_Device_ID)){ if($eSerial=$this->event->getEventBySerial($dSerial->vSerial)) echo $eSerial->vBadge_serial;}?></td>
					<td><?php  #if($eSerial)echo $eSerial->vReader_serial;?></td>
					<td><?php #if($Fac=$this->manager->getManagerById($user->vAssigned_Facility_ID)){echo $Fac->vFacility;}?></td>
					<td><?php #if($eSerial){if($rSerial=$this->reader->getReaderByFac($eSerial->vReader_serial)) echo $rSerial->vDescription;}?></td>
					<td><?php #if($eSerial=$this->event->getEventBySerial($dSerial->vSerial)) echo $eSerial->tTimestamp;?></td>-->
