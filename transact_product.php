<?php
$page_title = 'Transact product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(1);
?>
<?php
$product = find_by_id('products', (int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all_availability = find_all('availability');
$all_status = find_all('status');
$all_brands = find_all('brand');
$all_models = find_all('model');
$all_locations  = find_all('location');
$all_dep_points  = find_all('dep_point');
$all_status  = find_all('status');

if (!$product) {
    $session->msg("d", "Missing product id.");
    redirect('transaction.php');
}
?>

<?php
if (isset($_POST['add_transaction'])) {
    $req_fields = array('p_id', 'location_id', 'dep_point_id', 'status_id', 'date');
    validate_fields($req_fields);
    if (empty($errors)) {
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

        if ($db->query($sql)) {
            $sql  = "SELECT * FROM `transaction` LEFT JOIN products on products.id = transaction.product_id";
            $sql .= " WHERE transaction.date > '{$date}' AND products.id = '{$p_id}' ORDER BY transaction.date;";
            $result = $db->query($sql);
            if ($db->affected_rows()>1){
                $session->msg('s', "Transaction History added. Location&Status Not Changed");
            } else {
                //check_last_product_location($p_id);
                update_product_location($lctn_id, $p_id);
                update_product_dip_point($dep_pnt_id, $p_id);
                update_product_status($status_id, $p_id);
                $session->msg('s', "Transaction added. Location& Status Updated ");
            }
            redirect('transaction.php', false);
        } else {
            $session->msg('d', ' Sorry failed to add!');
            redirect('transaction.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('transaction.php', false);
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
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>

                <i class="fa-regular fa-file-lines"></i>
                <span>Transact Product</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="transact_product.php?id=<?php echo (int)$product['id'] ?>">
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
                    <tbody id="product_info">
                        <?php
                        if ($results = find_product_info_by_id($product['id'])) {
                            foreach ($results as $result) { ?>
                                <tr>
                                    <td> <?php echo $result['title']; ?> </td>
                                    <td> <?php echo $result['serial_number']; ?> </td>
                                    <input type="hidden" name="p_id" value="<?php echo $result['id']; ?>">
                                    <td>
                                        <select class="form-control select2" name="location_id">
                                            <option value="" disabled selected>-----------</option>
                                            <?php foreach ($all_locations as $cat) : ?>
                                                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $result['location_id']) {
                                                                                                echo " selected";
                                                                                            } ?>><?php echo $cat['office']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2" name="dep_point_id">
                                            <option value="" disabled selected>-----------</option>
                                            <?php foreach ($all_dep_points as $cat) : ?>
                                                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $result['dep_point_id']) {
                                                                                                echo " selected";
                                                                                            } ?>><?php echo $cat['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2" name="status_id">
                                            <option value="" disabled selected>-----------</option>
                                            <?php foreach ($all_status as $cat) : ?>
                                                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $result['status_id']) {
                                                                                                echo " selected";
                                                                                            } ?>><?php echo $cat['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td> <input type="date" class="form-control datePicker" name="date" data-date data-date-format="yyyy-mm-d\">
                                    </td>
                                    <td> <input type="text" class="form-control" name="remarks" value=""></td>
                                    <td> <button type="submit" name="add_transaction" class="btn btn-primary">Issue</button></td>
                                </tr><?php
                                    }
                                } else { ?>
                            <tr>
                                <td>No product found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>