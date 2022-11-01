<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
        <strong>
        
        <i class="fa-regular fa-file-lines"></i>
          <span>All Products</span>
        </strong>
         <div class="pull-right">
           <a href="add_product.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered display" id="data_table">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Photo</th>
                <th> Product Title </th>
                <th class="text-center" style="width: 10%;"> Categorie </th>
                <th class="text-center" style="width: 10%;"> Brand </th>
                <th class="text-center" style="width: 10%;"> Model </th>
                <th class="text-center" style="width: 10%;"> Serial Number </th>
                <th class="text-center" style="width: 10%;"> Availability </th>
                <th class="text-center" style="width: 10%;"> Status </th>
                <th class="text-center" style="width: 10%;"> Remarks </th>
                <th class="text-center" style="width: 10%;"> Location </th>
                <th class="text-center" style="width: 100px;"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td>
                  <?php if($product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                <?php endif; ?>
                </td>
                <td> <?php echo remove_junk($product['title']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['brand']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['model']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['serial_number']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['availability']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['status']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['remarks']); ?></td>
                <?php if(remove_junk($product['location'])){ ?>
                <td class="text-center"> <?php echo remove_junk($product['location'])."/ ".remove_junk($product['dep_point']);  ?></td>
                <?php }else{ ?>
                <td class="text-center"> ---- </td>
                <?php } ?>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip" style="margin-right:2px">
                    <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <a href="transact_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="Transact" data-toggle="tooltip" style="margin-right:2px">
                    <i class="fa-regular fa-plus"></i>  
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                    <i class="fa-regular fa-trash-can"></i>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
