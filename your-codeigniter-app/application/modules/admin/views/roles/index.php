<style>
    .topbar {
        display: flex;
        justify-content: space-between;
        padding-top : 10px;
    }
</style>
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
    <div class="box box-default" style="padding: 0px 20px 0px 20px">
        <div class="topbar">
            <div>
                <h3 class="box-title"><?= $pageTitle; ?></h3>
            </div>
            <div>
                <a href="<?=base_url('admin/roles/create');?>" class="btn btn-info" >Create New</a>
                <a href="<?=base_url('admin/roles/upload');?>" class="btn btn-warning" >Upload Roles</a>
            </div>
        </div>
        <div class="box-body">
            <table id="role-table" class="datatable table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role Name</th>
                        <th>Last Updated</th>
                        <th data-orderable="false">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $count = 1;
                        foreach( $roles as $role) {
                    ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?= $role->name ?></td>
                            <td><?= $role->created_at ?></td>
                            <td>
                                <a href="/admin/roles/edit/<?=$role->id?>" class="btn btn-flat btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                <a onclick="return confirm('Are you sure you want to delete this role?');" href="/admin/roles/delete/<?=$role->id?>" class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php 
                        $count++;
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>