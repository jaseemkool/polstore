<?php
  $page_title = 'All location';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $all_location = find_all('location')
?>
<?php
 if(isset($_POST['add_location'])){
   $req_field = array('location-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['location-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO location (office)";
      $sql .= " VALUES ('{$cat_name}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added location");
        redirect('location.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('location.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('location.php',false);
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
            <span>Add New Location</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="location.php">
            <div class="form-group">
                <input type="text" class="form-control" name="location-name" placeholder="Location Name">
            </div>
            <button type="submit" name="add_location" class="btn btn-primary">Add location</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          
        <i class="fa-regular fa-file-lines"></i>
          <span>All Locations</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Location</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_location as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($cat['office'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_location.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_location.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
