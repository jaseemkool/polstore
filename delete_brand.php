<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $brand = find_by_id('brand',(int)$_GET['id']);
  if(!$brand){
    $session->msg("d","Missing Band id.");
    redirect('brand.php');
  }
?>
<?php
  $delete_id = delete_by_id('brand',(int)$brand['id']);
  if($delete_id){
      $session->msg("s","Brand deleted.");
      redirect('brand.php');
  } else {
      $session->msg("d","Brand deletion failed.");
      redirect('brand.php');
  }
?>
