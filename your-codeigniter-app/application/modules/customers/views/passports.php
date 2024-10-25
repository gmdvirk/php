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
            <a href="<?=base_url('customers/passports/addnew');?>" class="btn btn-info btn-flat pull-right">Add new</a>
          </div>
          <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Pacts</th>
                  <th>Passport Type</th>
                  <th>Other Passport</th>
                  <th>PSO</th>
                  <th>Date Recieved</th>
                  <th>Date Returned</th>
                  <th>Notes</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                    $i=1;
                    if(!empty($passports)):
                  foreach($passports as $passport): ?> 
                  <tr>
                    <td><?php echo $i; $i++;?></td>
                    <td><?php echo $passport->LastName;?></td>
                    <td><?php echo $passport->FirstName;?></td>
                    <td><?php echo $passport->PACTS;?></td>
                    <td><?php echo $passport->Passport_Type;?></td>
                    <td><?php echo $passport->Other_Passport;?></td>
                    <td><?php echo $passport->PSO;?></td>
                    <td><?php echo $passport->Date_Received;?></td>
                    <td><?php echo $passport->Date_Returned;?></td>
                    <td><?php echo $passport->Notes;?></td>
                    <td>
                        <a href="<?=base_url('customers/passports/editPassport/'.$passport->id);?>" class="btn btn-flat btn-sm btn-info"><i class="fa fa-edit"></i></a>
                        <!--a onclick="return confirm('Are you sure you want to delete this customer?');" href="<?=base_url('customers/passports/deletePassport/'.$passport->id);?>" class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash"></i></a-->
                    </td>
                  </tr>
                  <?php endforeach;?>
                  <?php endif;?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Pacts</th>
                  <th>Passport Type</th>
                  <th>Other Passport</th>
                  <th>PSO</th>
                  <th>Date Recieved</th>
                  <th>Date Returned</th>
                  <th>Notes</th>
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