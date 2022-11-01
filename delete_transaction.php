<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  $d_sale = find_by_id('transaction',(int)$_GET['id']);
  if(!$d_sale){
    $session->msg("d","Missing sale id.");
    redirect('transaction.php');
  }
?>
<?php
  $delete_id = delete_by_id('transaction',(int)$d_sale['id']);
  if($delete_id){
      $session->msg("s","transaction deleted.");
      redirect('transaction.php');
  } else {
      $session->msg("d","transaction deletion failed.");
      redirect('transaction.php');
  }
?>
