<?php
  $page_title = 'All dep-point';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $all_dep_point = find_all('dep_point')
?>
<?php
 if(isset($_POST['add_dep-point'])){
   $req_field = array('dep-point-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['dep-point-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO dep_point (name)";
      $sql .= " VALUES ('{$cat_name}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added dep-point");
        redirect('dep-point.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('dep-point.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('dep-point.php',false);
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
            <span>Add New dep-point</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="dep-point.php">
            <div class="form-group">
                <input type="text" class="form-control" name="dep-point-name" placeholder="dep-point Name">
            </div>
            <button type="submit" name="add_dep-point" class="btn btn-primary">Add dep-point</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          
        <i class="fa-regular fa-file-lines"></i>
          <span>All dep-points</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>dep-point</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_dep_point as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_dep-point.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_dep-point.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
