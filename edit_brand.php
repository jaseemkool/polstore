<?php
  $page_title = 'Edit brand';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  //Display all catgories.
  $brand = find_by_id('brand',(int)$_GET['id']);
  if(!$brand){
    $session->msg("d","Missing brand id.");
    redirect('brand.php');
  }
?>

<?php
if(isset($_POST['edit_brand'])){
  $req_field = array('brand-name');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['brand-name']));
  if(empty($errors)){
        $sql = "UPDATE brand SET name='{$cat_name}'";
       $sql .= " WHERE id='{$brand['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Brand");
       redirect('brand.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('brand.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('brand.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           
        <i class="fa-regular fa-file-lines"></i>
           <span>Editing <?php echo remove_junk(ucfirst($brand['name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_brand.php?id=<?php echo (int)$brand['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="brand-name" value="<?php echo remove_junk(ucfirst($brand['name']));?>">
           </div>
           <button type="submit" name="edit_brand" class="btn btn-primary">Update brand</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
