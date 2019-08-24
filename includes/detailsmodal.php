<?php 
require_once '../core/init.php';
$id = $_POST['id'];
$id = (int)$id;
$sql1 = "SELECT *FROM products WHERE id = '$id' ";
$productQry = $db->query( $sql1 );
$product = mysqli_fetch_assoc( $productQry );
$brandId = $product['brand'];
$sql2 = "SELECT brand FROM brand WHERE id = '$brandId'";
$brandQry = $db->query( $sql2 );
$brand = mysqli_fetch_assoc( $brandQry );
$sizeString = $product['sizes'];
$sizeString = rtrim($sizeString,',');
$sizeArray = explode(',', $sizeString);

ob_start() ;
?>
<div class="modal fade details-1" id="details-modal" role="dialog" tabindex="-1" aria-labelledby="details-1" area-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="closeModal()" aria-label="close">
                        <span area-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-center"><?= $product['title'] ;?></h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <span id="modal_errors" class="bg-danger"></span>
                            <div class="col-sm-6">
                                <div class="centre-block"> 
                                    <img src="<?= $product['image'] ;?>" alt="<?= $product['title'] ;?>" class="details img-response">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h4>Details</h4>
                                <p><?= $product['description'] ;?></p>
                                <hr>
                                <p>Price: <?= $product['price'] ;?></p>
                                <p>Brand: <?= $brand['brand'] ;?></p>
                                <form action="add_cart.php" method="post" id="add_product_form">
                                     <input type="hidden" name="product_id" value="<?=$id;?>">
                                    <input type="hidden" name="available" id="available" value=""> 
                                    <div class="form-group">
                                        <div class="col-xs-3">
                                            <label for="quantity">Quantity</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" min="0">
                                        </div><br>
                                        <div class="col-xs-9">&nbsp</div>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <select class="form-control" id="size" name="size">
                                            <option value=""></option>
                                                <?php foreach ($sizeArray as $string) { 
                                                    $stringArray = explode(':', $string);
                                                    $size = $stringArray[0];
                                                    $available = $stringArray[1];
                                                     echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.' ('.$available.' Available )</option>';
                                                 } ?>
                                                
                                            
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal" onclick="closeModal()">Close</button>
                    <button class="btn btn-sm btn-warning"  onclick="add_to_cart();return false;" ><span class="glyphicon glyphicon-shopping-cart"></span>Add To Cart</button>

                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery('#size').change(function(){
           var available = jQuery('#size option:selected').data("available");
            jQuery('#available').val(available);
        });
        
       function closeModal() {
         jQuery('#details-modal').modal('hide');
         setTimeout(function() {
            jQuery('#details-modal').remove();
            jQuery('.modal-backdrop').remove();
         },500);
       } 
    </script>
<?php echo ob_get_clean(); ?>