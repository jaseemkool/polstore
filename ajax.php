<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}

$all_locations  = find_all('location');
$all_dep_points  = find_all('dep_point');
$all_status  = find_all('status');

?>
 
<?php
// Auto suggetion
$html = '';
if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
  $products = find_product_by_title($_POST['product_name']);
  if ($products) {
    foreach ($products as $product) :
      $html .= "<section><h5>";
      if(remove_junk($product['office'])){
      $html .= $product['office'] . " - " . $product['dep_point'] . " - " . $product['title'];
      }else{
      $html .= "-----" . " - " . $product['title'];
      }
      $html .= "</h5><ul>";
      $html .= "<a href=\"transact_product.php?id=" . $product['id'] . " \"";
      $html .= "<li class=\"list-group-item\" title =\" " . $product['office'] . $product['dep_point'] . "\" >";
      $html .= $product['serial_number']."  <button title=\"Transact this product\" name=\"transact\" class=\"btn btn-primary\">Transact</button>";
      $html .= "</li></a>";
      $html .= "</ul></section>";
    // $html .= "<li class=\"list-group-item\" title =\" " . $product['office'] . " Sl. No:- " . $product['serial_number'] . "\">";
    // $html .= $product['title'];
    // $html .= "</li>";
    endforeach;
  } else {

    //$html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">';
    $html .= 'Not found';
    $html .= "</li>";
  }

  echo json_encode($html);
}



if (isset($_POST['location_id'])) {

  if(remove_junk($db->escape($_POST['location_id']))){$location_id = remove_junk($db->escape($_POST['location_id']));}else{$location_id = "location_id";}
  if(remove_junk($db->escape($_POST['dep_point_id']))){$dep_point_id = remove_junk($db->escape($_POST['dep_point_id']));}else{$dep_point_id = "dep_point_id";}
  if(remove_junk($db->escape($_POST['categorie_id']))){$categorie_id = remove_junk($db->escape($_POST['categorie_id']));}else{$categorie_id = "categorie_id";}
  if(remove_junk($db->escape($_POST['brand_id']))){$brand_id = remove_junk($db->escape($_POST['brand_id']));}else{$brand_id = "brand_id";}
  if(remove_junk($db->escape($_POST['model_id']))){$model_id = remove_junk($db->escape($_POST['model_id']));}else{$model_id = "model_id";}
  if(remove_junk($db->escape($_POST['availability_id']))){$availability_id = remove_junk($db->escape($_POST['availability_id']));}else{$availability_id = "availability_id";}
  if(remove_junk($db->escape($_POST['status_id']))){$status_id = remove_junk($db->escape($_POST['status_id']));}else{$status_id = "status_id";}
  if ($results = find_product_by_all_fields($location_id,$dep_point_id,$categorie_id,$brand_id,$model_id,$availability_id,$status_id)) {
    //if($results = find_all('products')){
    foreach ($results as $result) {

      $html .= "<tr>";
      $html .= "<td>{$result['office']}</td>";
      $html .= "<td>{$result['dep_point']}</td>";
      $html .= "<td>{$result['title']}</td>";
      $html  .= "<td>{$result['categorie']}</td>";
      $html  .= "<td>{$result['brand']}</td>";
      $html  .= "<td>{$result['model']}</td>";
      $html  .= "<td>{$result['serial_number']}</td>";
      $html  .= "<td>{$result['availability']}</td>";
      $html  .= "<td>{$result['status']}</td>";
      $html  .= "<td>{$result['remarks']}</td>";
      $html  .= "<td><a href=\"transact_product.php?id={$result['id']}\"> ";
      $html  .= "<button title=\"Transact this product\" name=\"transact\" class=\"btn btn-primary\">Transact</button>";
      $html  .= "</a></td>";
      $html  .= "</tr>";
    }
  } else {
    $html = '<tr><td>No Result Found</td></tr>';
  }

  echo json_encode($html);
}
?>