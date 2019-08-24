<?php
require_once 'core/init.php';
include 'includes/header.php'; 
include 'includes/navigation.php'; 
include 'includes/leftbar.php'; 
$sql         = "SELECT * FROM products WHERE featured = 1 ";
$featuredQry = $db->query( $sql );
?> 
    <!-- main content -->
        <div class="col-md-8">
            <div class="row">
                <h2 class="text-center">Feature Products</h2>
                <?php while( $product = mysqli_fetch_assoc( $featuredQry ) ) : 
                ?>
                <div class="col-md-3">
                    <h4><?php echo $product['title'] ?></h4>
                    <img src="<?php echo $product['image'] ?>" alt="<?php echo $product['title'] ?>" class="img-thumb" />
                    <p class="list-price text-danger">List Price:<s><?php echo $product['list_price'] ?></s></p>
                    <p class="price">Our Price: <?php echo $product['price'] ?></p>
                    <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id'] ?> )" >Details</button>
                </div>   
                <?php endwhile ; ?> 
            </div>
        </div>
<?php include 'includes/rightbar.php'; ?>       
 <?php include 'includes/footer.php'; ?>    