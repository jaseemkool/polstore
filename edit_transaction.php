<?php
$page_title = 'Edit transaction';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>
<?php
$transaction = find_by_id('transaction', (int)$_GET['id']);
if (!$transaction) {
  $session->msg("d", "Missing transaction id.");
  redirect('transaction.php');
}
?>
<?php $product = find_by_id('products', $transaction['product_id']);

$all_locations  = find_all('location');
$all_dep_points  = find_all('dep_point');
$all_status  = find_all('status');
?>
<?php

if (isset($_POST['update_transaction'])) {
  $req_fields = array('product_id', 'location_id', 'dep_point_id', 'status_id', 'date');
  validate_fields($req_fields);
  if (empty($errors)) {
    $p_id      = $db->escape((int)$_POST['product_id']);
    $location_id     = $db->escape((int)$_POST['location_id']);
    $dep_point_id   = $db->escape($_POST['dep_point_id']);
    $status_id   = $db->escape($_POST['status_id']);
    $remarks      = $db->escape($_POST['remarks']);
    $date      = $db->escape($_POST['date']);
    $s_date    = make_date();

    $sql  = "UPDATE transaction SET";
    $sql .= " product_id= '{$p_id}',location_id={$location_id},dep_point_id='{$dep_point_id}'";
    $sql .= ",status_id='{$status_id}',remarks='{$remarks}',date='{$date}',update_date='{$s_date}'";
    $sql .= " WHERE id ='{$transaction['id']}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      update_product_location($location_id, $p_id);
      update_product_dip_point($dep_pnt_id,$p_id);
      update_product_status($status_id,$p_id);
      $session->msg('s', "transaction updated.");
      redirect('transaction.php?id=' . $transaction['id'], false);
    } else {
      $session->msg('d', ' Sorry failed to updated!');
      redirect('transaction.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('transaction.php?id=' . (int)$transaction['id'], false);
  }
}

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading clearfix">
        <strong>
          
        <i class="fa-regular fa-file-lines"></i>
          <span>All transactions</span>
        </strong>
        <div class="pull-right">
          <a href="transaction.php" class="btn btn-primary">Show all transactions</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <th> Product title </th>
            <th> Serial Number </th>
            <th> Location </th>
            <th> Point </th>
            <th> Status </th>
            <th> Date</th>
            <th> Remarks</th>
            <th> Action</th>
          </thead>
          <tbody id="product_info">
            <tr>
              <form method="post" action="edit_transaction.php?id=<?php echo (int)$transaction['id']; ?>">
                <td id="s_name">
                  <input type="text" class="form-control" id="sug_input" name="title" readonly value="<?php echo remove_junk($product['title']); ?>">
                  <div id="result" class="list-group"></div>
                </td>
                <input type="hidden" name="product_id" value="<?php echo remove_junk($product['id']); ?>">
                <td id="s_name">
                  <input type="text" class="form-control" id="sug_input" name="serial_number" readonly value="<?php echo remove_junk($product['serial_number']); ?>">
                  <div id="result" class="list-group"></div>
                </td>
                <td>
                  <select class="form-control" name="location_id">
                    <?php foreach ($all_locations as $cat) : ?>
                      <option value=" <?php echo $cat['id'];?>" 
                      <?php if ($cat['id']==(int)$transaction['location_id']){echo "selected";}?>>
                        <?php echo $cat['office'];?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td>
                  <select class="form-control" name="dep_point_id">
                    <?php foreach ($all_dep_points as $cat) : ?>
                      <option value=" <?php echo $cat['id'];?>" 
                      <?php if ($cat['id']==(int)$transaction['dep_point_id']){echo "selected";}?>>
                        <?php echo $cat['name'];?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td>
                  <select class="form-control" name="status_id">
                    <?php foreach ($all_status as $cat) : ?>
                      <option value=" <?php echo $cat['id'];?>" 
                      <?php if ($cat['id']==(int)$transaction['status_id']){echo "selected";}?>>
                        <?php echo $cat['name'];?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td id="s_date">
                  <input type="date" class="form-control datepicker" name="date" data-date-format="" value="<?php echo remove_junk($transaction['date']); ?>">
                </td>
                <td id="remarks">
                  <input type="text" class="form-control" id="remarks" name="remarks" value="<?php echo remove_junk($transaction['remarks']); ?>">
                </td>
                <td>
                  <button type="submit" name="update_transaction" class="btn btn-primary">Update transaction</button>
                </td>
              </form>
            </tr>
          </tbody>
        </table>

      </div>
    </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>