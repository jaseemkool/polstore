<?php
  $page_title = 'All status';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $all_availability = find_all('status')
?>
<?php
 if(isset($_POST['add_status'])){
   $req_field = array('status-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['status-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO status (name)";
      $sql .= " VALUES ('{$cat_name}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added status");
        redirect('status.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('status.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('status.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>

  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
   <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Status</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="status.php">
            <div class="form-group">
                <input type="text" class="form-control" name="status-name" placeholder="Status Name">
            </div>
            <button type="submit" name="add_status" class="btn btn-primary">Add Status</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
         
        <i class="fa-regular fa-file-lines"></i>
          <span>All Working Statuses</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>status</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_availability as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_status.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_status.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </div>
                    </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
