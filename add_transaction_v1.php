<?php
  $page_title = 'Add Transaction';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php

  if(isset($_POST['add_transaction'])){
    $req_fields = array('p_id','location_id','dep_point_id','status_id', 'date' );
    validate_fields($req_fields);
        if(empty($errors)){
          $p_id      = $db->escape((int)$_POST['p_id']);
          $status_id     = $db->escape((int)$_POST['status_id']);
          $lctn_id   = $db->escape((int)$_POST['location_id']);
          $dep_pnt_id   = $db->escape((int)$_POST['dep_point_id']);
          $date      = $db->escape($_POST['date']);
          $remarks      = $db->escape($_POST['remarks']);

          $sql  = "INSERT INTO transaction (";
          $sql .= " product_id,location_id,dep_point_id,status_id,remarks,date";
          $sql .= ") VALUES (";
          $sql .= "'{$p_id}','{$lctn_id}','{$dep_pnt_id}','{$status_id}','{$remarks}','{$date}'";
          $sql .= ")";

                if($db->query($sql)){
                  update_product_location($lctn_id,$p_id);
                  update_product_dip_point($dep_pnt_id,$p_id);
                  update_product_status($status_id,$p_id);
                  $session->msg('s',"Transaction added. ");
                  redirect('transaction.php', false);
                } else {
                  $session->msg('d',' Sorry failed to add!');
                  redirect('transaction.php', false);
                }
        } else {
           $session->msg("d", $errors);
           redirect('transaction.php',false);
        }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Search by Serial Number/ Title">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Find Product</button>
            </span>
         </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Transaction</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_transaction.php">
         <table class="table table-bordered">
           <thead>
            <th> Product </th>
            <th> Serial Number </th>
            <th> Location </th>
            <th> Point </th>
            <th> Status </th>
            <th> Date</th>
            <th> Remarks</th>
            <th> Action</th>
           </thead>
             <tbody  id="product_info"> </tbody>
         </table>
       </form>
      </div>
    </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>
