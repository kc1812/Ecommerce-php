<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if( !is_logged_in()){
	login_error_redirect();
}
include 'includes/header.php';
include 'includes/navigation.php';
$dbPath = '';

if( isset($_GET['delete'])){
	$deleteId = (int)$_GET['delete'];
	$productResults = $db->query("UPDATE products SET deleted = 1 WHERE id = $deleteId");
	header('Location: products.php');
}
if ( isset( $_GET['add'] ) || isset( $_GET['edit'] ) ) {
	$qryBrand = $db->query( "SELECT * FROM brand ORDER BY brand" );
	$qryCategory = $db->query( "SELECT * FROM categories WHERE parent = 0 ORDER BY category" );
	$title = (( isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):'');
	$brand = (( isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
	$parent = (( isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
	$category = (( isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
	$price = (( isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):'');
	$list_price = (( isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):'');
	$sizes = (( isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):'');
	$sizes = rtrim($sizes,',');
	$description = (( isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):'');
	$saved_image = '';
	if (isset($_GET['edit'])) {
		$editId = (int)$_GET['edit'];
		$productResults = $db->query("SELECT * FROM products WHERE id = $editId");
		$product = mysqli_fetch_assoc($productResults);

		if( isset($_GET['delete_image'])){
			$image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
			unlink($image_url);
			$db->query("UPDATE products SET image = '' WHERE id = '$editId'");
			header('Location: products.php?edit='.$editId);
		}

		$title = (( isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
		$brand = (( isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
		$category = (( isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):$product['categories']);
		$qryCat = $db->query( "SELECT * FROM categories WHERE id='$category'" );
		$parentResult = mysqli_fetch_assoc($qryCat);
		$parent = (( isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$parentResult['parent']);
		$price = (( isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
		$list_price = (( isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
		$sizes = (( isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):$product['sizes']);
		$sizes = rtrim($sizes,',');
		$description = (( isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):$product['description']);
		$saved_image = (($product['image'] != '')?$product['image']:'');
		$dbPath = $saved_image;
		if( !empty($sizes)){
			$sizeString = sanitize( $sizes );
			$sizeString = rtrim( $sizeString,','); 
			$sizesArray = explode(',', $sizeString );
			$sArray = array();
			$qArray = array(); 
			foreach ($sizesArray as $ss ) {
				$s = explode(':', $ss);
				$sArray[] = $s[0];
				$qArray[] = $s[1];
			}
		}else{ $sizesArray = array();}
	}
	if( $_POST){
		$errors = array();
		$required = array('title','price','parent','child','sizes') ;
		foreach ($required as $field) {
			if($_POST[$field] == ''){
				$errors[] = 'All Fields with an astrik are required.';
				break;
			}
		}
		if ( !empty($_FILES)) {
		
			$photo = $_FILES['photo'];
			$name = $photo['name'];
			$nameArray = explode('.', $name);
			$fileName = $nameArray[0];
			$fileExt = $nameArray[1];
			$mime = explode('/', $photo['type']);
			$mimeType = $mime[0];
			$mimeExt = $mime[1];
			$tmpLoc = $photo['tmp_name'];
			$fileSize = $photo['size'];
			$allowed = array('png','jpg','jpeg','gif');
			$uploadName = md5(microtime()).'.'.$fileExt;
			$uploadPath = BASEURL.'/images/products/'.$uploadName;
			$dbPath = '/ecom/images/products/'.$uploadName;

			if( $mimeType != 'image'){
				$errors[] = 'The file must be an image.';
			}
			if ( !in_array($fileExt, $allowed)) {
				$errors[] = 'The file extension must be a png,jpg,jpeg or gif.';
			}
			if ( $fileSize > 15000000) {
				$errors[] = 'The file size must be less than 15MB.';
			}
			if ( $fileExt != $mimeExt && ( $mimeExt=='jpeg' && $fileExt !='jpg') ) {
				$errors[] = 'File extension doesnot match the file'; 
			}
		}
		if( !empty( $errors )){
			echo display_errors( $errors );
		}else{
			if(!empty($_FILES)){
				move_uploaded_file($tmpLoc,$uploadPath);
			}
			$insertSql = "INSERT INTO products(title,price,list_price,brand,categories,sizes,image,description) VALUES ('$title','$price','$list_price','$brand','$category','$sizes','$dbPath','$description')";

			if( isset($_GET['edit']) ){
				$insertSql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand', categories = '$category', sizes = '$sizes' , image = '$dbPath', description = '$description' WHERE id = '$editId' ";
			}
			$db->query( $insertSql );
			header('Location: products.php');
		}
	}
	?>
	<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A')?> Products</h2>
	<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$editId:'add=1')?>" method="POST" enctype="multipart/form-data">
		<div class="form-group col-md-3">
			<label for="title">Title*:</label>
			<input type="text" name="title" id="title" class="form-control" value="<?=$title?>">
		</div>
		<div class="form-group col-md-3">
			<label for="brand">Brand*:</label>
			<select class="form-control" id="brand" name="brand">
				<option value="<?= (($brand == '')?'':'')?>"></option>
				<?php while( $b = mysqli_fetch_assoc( $qryBrand) ): ?>
					<option value="<?=$b['id']?>"<?=(($brand == $b['id'] )?' selected':'')?>><?=$b['brand']?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="parent">Parent Category*:</label>
			<select class="form-control" id="parent" name="parent">
				<option value="<?= (($parent == '')?'':'')?>"></option>
				<?php while( $p = mysqli_fetch_assoc( $qryCategory ) ): ?>
					<option value="<?=$p['id']?>"<?=(($parent== $p['id'] )?' selected':'')?>><?=$p['category']?></option>
				<?php endwhile; ?>
			</select>
		</div>
		 <div class="form-group col-md-3">
			<label for="child" >Child Category*:</label>
			<select class="form-control" id="child" name="child">
				
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="price">Price*:</label>
			<input type="text" name="price" id="price" class="form-control" value="<?=$price;?>">
		</div>
		<div class="form-group col-md-3">
			<label for="list_price">List price:</label>
			<input type="text" name="list_price" id="list_price" class="form-control" value="<?=$list_price;?>">
		</div>
		<div class="form-group col-md-3">
			<label>Quantity & Sizes*:</label>
			<button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false" >Quantity & Sizes</button>
		</div>
		<div class="form-group col-md-3">
			<label for="sizes">Quantity & Sizes Preview*:</label>
			<input type="text" name="sizes" id="sizes" class="form-control" value="<?=$sizes;?>" readonly>
		</div>
		<div class="form-group col-md-6">
		<?php if($saved_image != '') :?>
			<div class="saved-image">
				<img src="<?=$saved_image;?>" alt="saved-image" >
				<a href="products.php?delete_image=1&edit=<?=$editId?>" class="text-danger">Delete Image</a>
			</div>
		<?php else:?>
			<label for="photo">Product Photo*:</label>
			<input type="file" name="photo" id="photo" class="form-control">
		<?php endif;?>
		</div>
		<div class="form-group col-md-6">
			<label for="description">Description*:</label>
			<textarea name="description" id="description" class="form-control" rows="6"><?=$description;?></textarea>
		</div>
		<div class="form-group pull-right">
		<a href="products.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add')?> Product" class="btn btn-success">
		</div><div class="clearfix"></div>
	</form>
	<!-- Modal -->
	<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="sizesModalLabel">Sizes & Quantity</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="container-fluid">
	      		<?php for($i=1;$i<=12;$i++): ?>
	      		<div class="form-group col-md-4">
	      			<label for="size<?=$i?>">Size:</label>
	      			<input type="text" name="size<?=$i?>" id="size<?=$i?>" class="form-control" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'')?>">
	      		</div>
	      		<div class="form-group col-md-2">
	      			<label for="qty<?=$i?>">Quantity:</label>
	      			<input type="number" name="qty<?=$i?>" id="qty<?=$i?>" class="form-control" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'')?>" min
	      			"0">
	      		</div>
	      	<?php endfor; ?>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>
<?php }else {
	$sqlProducts = "SELECT * FROM products WHERE deleted = 0";
	$qryProducts = $db->query( $sqlProducts );
	if ( isset($_GET['featured'])) {
		$id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$sqlFeatured = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
		$qryFeatured = $db->query( $sqlFeatured );
		header('Location: products.php');
	}
	?>
	<h2 class="text-center">Products</h2>
	<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a>
	<div class="clearfix"></div>
	<hr>
	<table class="table table-bordered table-condensed table-striped">
		<thead><th></th><th>Product</th><th>Price</th><th>category</th><th>Featured</th><th>Sold</th></thead>
		<tbody>
			<?php while ( $product = mysqli_fetch_assoc( $qryProducts )) :
				$childId  = $product['categories'];
				$sqlChild = "SELECT * FROM categories WHERE id = '$childId'";
				$qryChild = $db->query( $sqlChild );
				$child 	  = mysqli_fetch_assoc( $qryChild );
				$parentId = $child['parent'];
				$sqlParent = "SELECT * FROM categories WHERE id = '$parentId'";
				$qryParent = $db->query( $sqlParent );
				$parent    = mysqli_fetch_assoc( $qryParent );
				$category  = $parent['category'].'-'.$child['category'];
			?>
				<tr>
					<td>
						<a href="products.php?edit=<?=$product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
						<a href="products.php?delete=<?=$product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
					</td>
					<td><?=$product['title']; ?></td>
					<td><?=money( $product['price'] ); ?></td>
					<td><?= $category?></td>
					<td><a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0')?>&id=<?=$product['id']?>"  class="btn btn-xs btn-default">
					<span class="glyphicon glyphicon-<?=(($product['featured'] == 1)?'minus':'plus')?>"></span>
					</a>&nbsp <?= (($product['featured'] == 1)?'Featured Product':' ')?></td> 
					<td></td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
<?php } include 'includes/footer.php';?>
<script>
	jQuery('document').ready( function(){
		get_child_options('<?=$category?>');
	});
</script>