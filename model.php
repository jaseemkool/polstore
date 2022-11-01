<?php
  $page_title = 'All models';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $all_model = find_all_model();
  $all_brand = find_all('brand');
?>
<?php
 if(isset($_POST['add_model'])){
   $req_field = array('model-name','brand-name');
   validate_fields($req_field);
   $brand_name = remove_junk($db->escape($_POST['brand-name']));
   $model_name = remove_junk($db->escape($_POST['model-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO model (name, brand_id)";
      $sql .= " VALUES ('{$model_name}','{$brand_name}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added Model");
        redirect('model.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('model.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('model.php',false);
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
            <span>Add New Model</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="model.php">
          <div class="form-group">
          <select class="form-control" name="brand-name">
          <option>Select Brand</option>
            <?php foreach($all_brand as $brand): ?>
                <option value="<?php echo $brand['id'];?>"><?php echo remove_junk(ucfirst($brand['name'])); ?></option>
            <?php endforeach;?>
                </select>
          </div>  
          <div class="form-group">
                <input type="text" class="form-control" name="model-name" placeholder="Brand Name">
            </div>
            <button type="submit" name="add_model" class="btn btn-primary">Add Model</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          
        <i class="fa-regular fa-file-lines"></i>
          <span>All Models</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Brands</th>
                    <th>Models</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_model as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($cat['brand'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_model.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_model.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
