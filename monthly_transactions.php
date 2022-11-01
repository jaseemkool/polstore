<?php
  $page_title = 'Monthly Transactions';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
$year  = date('Y');
$month = date('m');
 $transactions = monthlyTransactions($year,$month);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
        <i class="fa-regular fa-file-lines"></i>
          <span>Monthly Transactions</span>
        </strong>
        <!-- <div class="pull-right">
          <a href="add_Transaction.php" class="btn btn-primary">Add Transaction</a>
        </div> -->
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped display" id="data_table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Product title </th>
              <th class="text-center" style="width: 15%;"> Serial Number</th>
              <th class="text-center" style="width: 15%;"> Location/ Dep Point</th>
              <th class="text-center" style="width: 15%;"> Date </th>
              <th class="text-center" style="width: 15%;"> Remarks </th>
              <th class="text-center" style="width: 100px;"> Actions </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($transactions as $transaction) : ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk($transaction['title']); ?></td>
                <td class="text-center"><?php echo $transaction['serial_number']; ?></td>
                <td class="text-center"><?php echo remove_junk($transaction['office']);
                                        echo "/ ";
                                        echo remove_junk($transaction['dep_point']); ?></td>
                <td class="text-center"><?php echo $transaction['date']; ?></td>
                <td class="text-center"><?php echo $transaction['remarks']; ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_transaction.php?id=<?php echo (int)$transaction['id']; ?>" class="btn btn-warning btn-xs" title="Edit" data-toggle="tooltip">
                    <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <a href="delete_transaction.php?id=<?php echo (int)$transaction['id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                    <i class="fa-regular fa-trash-can"></i>
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

<?php include_once('layouts/footer.php'); ?>
