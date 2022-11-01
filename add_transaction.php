<?php
$page_title = 'Add Transaction';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);

$all_subunits = ['Malappuram'];
$all_subdivisions = find_all('subdivision');
$all_locations = find_all('location');
$all_dep_points = find_all('dep_point');
$all_categories = find_all('categories');
$all_availability = find_all('availability');
$all_status = find_all('status');
$all_brands = find_all('brand');
$all_models = find_all('model');
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
      update_product_location($lctn_id, $p_id);
      update_product_dip_point($dep_pnt_id, $p_id);
      update_product_status($status_id, $p_id);
      $session->msg('s', "Transaction added. ");
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
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <!-- <form method="get" action="transact_product.php" autocomplete="off" id="sug-form"> -->
    <div class="form-group">
      <div class="input-group">
        <input type="text" id="sug_input" class="form-control" name="title" placeholder="Search by Serial Number/ Title">
        <span class="input-group-btn">
          <button type="submit" class="btn btn-primary" onclick="findProductButton()">Find Product</button>
        </span>
      </div>
      <div id="result" class="list-group"></div>
    </div>
    <!-- </form> -->
  </div>
</div>
<div class="row">

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <i class="fa-solid fa-magnifying-glass"></i>
          <span>Search Product By Location</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="ajax.php" autocomplete="off" id="search-form">
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <label>Subunit</label>
                <select class="form-control select2" name="subunit">
                  <?php foreach ($all_subunits as $subunit) : ?>
                    <option value="<?php echo $subunit ?>">
                      <?php echo $subunit ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label>Subdivision</label>
                <select class="form-control select2" name="subdivision">
                  <option value="">Select Subdivision</option>
                  <?php foreach ($all_subdivisions as $subdivision) : ?>
                    <option value="<?php echo (int)$subdivision['id'] ?>">
                      <?php echo $subdivision['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control select2" name="location">
                  <option value="">Select Office</option>
                  <?php foreach ($all_locations as $location) : ?>
                    <option value="<?php echo (int)$location['id'] ?>">
                      <?php echo $location['office'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control select2" name="dep_point">
                  <option value="">Select Deployment Point</option>
                  <?php foreach ($all_dep_points as $dep_point) : ?>
                    <option value="<?php echo (int)$dep_point['id'] ?>">
                      <?php echo $dep_point['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control select2" name="categorie">
                  <option value="">Select Product Categorie</option>
                  <?php foreach ($all_categories as $categorie) : ?>
                    <option value="<?php echo (int)$categorie['id'] ?>">
                      <?php echo $categorie['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control select2" name="brand">
                  <option value="">Select Product Brand</option>
                  <?php foreach ($all_brands as $brand) : ?>
                    <option value="<?php echo (int)$brand['id'] ?>">
                      <?php echo $brand['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control select2" name="model">
                  <option value="">Select Product Model</option>
                  <?php foreach ($all_models as $model) : ?>
                    <option value="<?php echo (int)$model['id'] ?>">
                      <?php echo $model['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control select2" name="availability">
                  <option value="">Select Availability</option>
                  <?php foreach ($all_availability as $availability) : ?>
                    <option value="<?php echo (int)$availability['id'] ?>">
                      <?php echo $availability['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control select2" name="status">
                  <option value="">Select Status</option>
                  <?php foreach ($all_status as $status) : ?>
                    <option value="<?php echo (int)$status['id'] ?>">
                      <?php echo $status['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <span class="input-group-btn">
                  <button name="search_product" type="submit" class="btn btn-primary">Search Product</button>
                </span>
              </div>
            </div>
          </div>
        </form>
        <table class="table table-bordered" id="data_table">
          <thead>
            <th class="text-center" style="width: 10%;"> Location </th>
            <th class="text-center" style="width: 10%;"> Dep Point </th>
            <th> Product Title </th>
            <th class="text-center" style="width: 10%;"> Categorie </th>
            <th class="text-center" style="width: 10%;"> Brand </th>
            <th class="text-center" style="width: 10%;"> Model </th>
            <th class="text-center" style="width: 10%;"> Serial Number </th>
            <th class="text-center" style="width: 10%;"> Availability </th>
            <th class="text-center" style="width: 10%;"> Status </th>
            <th class="text-center" style="width: 10%;"> Remarks </th>
            <th class="text-center" style="width: 100px;"> Actions </th>
          </thead>
          <tbody id="product_info"> </tbody>
        </table>

      </div>
    </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>