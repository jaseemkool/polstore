<?php
$page_title = 'Edit product';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
?>
<?php
$product = find_by_id('products', (int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all_availability = find_all('availability');
$all_status = find_all('status');
$all_brands = find_all('brand');
$all_models = find_all('model');
if (!$product) {
  $session->msg("d", "Missing product id.");
  redirect('product.php');
}
?>
<?php
if (isset($_POST['product'])) {
  $req_fields = array('product-title', 'product-categorie','product-brand', 'product-serial-number', 'product-availability', 'product-status');
  validate_fields($req_fields);

  if (empty($errors)) {
    $p_name  = remove_junk($db->escape($_POST['product-title']));
    $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
    $p_brand   = remove_junk($db->escape($_POST['product-brand']));
    $p_model   = remove_junk($db->escape($_POST['product-model']));
    $p_slnmbr   = remove_junk($db->escape($_POST['product-serial-number']));
    $p_avl   = remove_junk($db->escape($_POST['product-availability']));
    $p_sts  = remove_junk($db->escape($_POST['product-status']));
    $p_remarks   = remove_junk($db->escape($_POST['product-remarks']));
    if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
      $media_id = '0';
    } else {
      $media_id = remove_junk($db->escape($_POST['product-photo']));
    }
    $date    = make_date();
    $query   = "UPDATE products SET";
    $query  .= " title ='{$p_name}', serial_number ='{$p_slnmbr}',";
    $query  .= " availability_id ='{$p_avl}', status_id  ='{$p_sts}', brand_id='{$p_brand}', model_id = '{$p_model}',";
    $query  .= "categorie_id ='{$p_cat}',media_id='{$media_id}',remarks='{$p_remarks}',date='{$date}'";
    $query  .= " WHERE id ='{$product['id']}'";
    $result = $db->query($query);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Product updated ");
      redirect('product.php', false);
    } else {
      $session->msg('d', ' Sorry failed to updated!');
      redirect('edit_product.php?id=' . $product['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_product.php?id=' . $product['id'], false);
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
        <span>Edit Product</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-12">
        <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-th-large"></i>
              </span>
              <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['title']); ?>">
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control select2" name="product-categorie">
                  <option value=""> Select a categorie</option>
                  <?php foreach ($all_categories as $cat) : ?>
                    <option value="<?php echo (int)$cat['id']; ?>" <?php if ($product['categorie_id'] === $cat['id']) : echo "selected";
                                                                    endif; ?>>
                      <?php echo remove_junk($cat['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control select2" name="product-photo">
                  <option value=""> No image</option>
                  <?php foreach ($all_photo as $photo) : ?>
                    <option value="<?php echo (int)$photo['id']; ?>" <?php if ($product['media_id'] === $photo['id']) : echo "selected";
                                                                    endif; ?>>
                      <?php echo $photo['file_name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control select2" name="product-brand">
                  <option value=""> Select a brand</option>
                  <?php foreach ($all_brands as $brand) : ?>
                    <option value="<?php echo (int)$brand['id']; ?>" <?php if ($product['brand_id'] === $brand['id']) : echo "selected";
                                                                    endif; ?>>
                      <?php echo remove_junk($brand['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control select2" name="product-model">
                  <option value=""> Select a model</option>
                  <?php foreach ($all_models as $model) : ?>
                    <option value="<?php echo (int)$model['id']; ?>" <?php if ($product['model_id'] === $model['id']) : echo "selected";
                                                                    endif; ?>>
                      <?php echo $model['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty">Serial Number</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="text" class="form-control" name="product-serial-number" value="<?php echo remove_junk($product['serial_number']); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty">Availability</label>
                  <select class="form-control select2" name="product-availability">
                    <option value=""> Select availability</option>
                    <?php foreach ($all_availability as $cat) : ?>
                      <option value="<?php echo (int)$cat['id']; ?>" <?php if ($product['availability_id'] === $cat['id']) : echo "selected";
                                                                      endif; ?>>
                        <?php echo remove_junk($cat['name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="qty">Status</label>
                  <select class="form-control select2" name="product-status">
                    <option value=""> Select status</option>
                    <?php foreach ($all_status as $cat) : ?>
                      <option value="<?php echo (int)$cat['id']; ?>" <?php if ($product['status_id'] === $cat['id']) : echo "selected";
                                                                      endif; ?>>
                        <?php echo remove_junk($cat['name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-th-large"></i>
              </span>
              <input type="text" class="form-control" name="product-remarks" value="<?php echo remove_junk($product['remarks']); ?>">
            </div>
          </div>
          <button type="submit" name="product" class="btn btn-danger">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>