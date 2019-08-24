<?php 
require_once '../core/init.php';
if( !is_logged_in()){
	login_error_redirect();
}
include 'includes/header.php';
include 'includes/navigation.php';
$sql    = "SELECT * FROM brand ORDER BY brand";
$brandQry = $db->query( $sql );
$errors = array();

if( isset( $_GET['edit'] ) && !empty( $_GET['edit'] ) ) {
	$edit_id = (int)$_GET['edit'];
	$edit_id = sanitize( $edit_id );
	$sqlEdit = "SELECT * FROM brand WHERE id = '$edit_id'";
	$qryEdit = $db->query( $sqlEdit );
	$brandDetails = mysqli_fetch_assoc( $qryEdit );
}
if( isset( $_GET['delete'] ) && !empty( $_GET['delete'] ) ) {
	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize( $delete_id );
	$sqlDel = "DELETE FROM brand WHERE id = '$delete_id'";
	$qryDel = $db->query( $sqlDel  );
	header('Location: brands.php');
}
if ( isset( $_POST['add_submit'] ) ) {
	$brand = sanitize( $_POST['brand'] );
	//check if brand field is empty//
	if ( $_POST['brand'] == '') {
		$errors[] .= 'You must enter a brand';
	}
	//check if brand already exists
	$sql1 = "SELECT * FROM brand WHERE brand ='$brand'";
	if( isset($_GET['edit']) ) {
		$sql1 = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";
	}
	$result = $db->query( $sql1 );
	$count = mysqli_num_rows( $result );
	if( $count > 0 ){
		$errors[] .= $brand.' already exists. Choose another brand...';
	}
	//display errors
	if( !empty( $errors ) ) {
		echo display_errors( $errors );
	}else {
		//add brand if no errors
		$sqlAdd = "INSERT INTO brand(brand) VALUES ('$brand')";
		if( isset($_GET['edit']) ) {
			$sqlAdd = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id' ";
		}
		$qryAdd = $db->query( $sqlAdd );
		header('Location: brands.php');
	}
}
?>
<h2 class="text-center">Brands</h2>
<div class="text-center">
	<form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'')?>" method="post">
		<div class="form-group">
			<?php 
			$brandValue = NULL;
			if( isset($_GET['edit']) ){
				$brandValue = sanitize($brandDetails['brand']);
			}else{
				if( isset( $_POST['add_submit'] ) ) {
					$brandValue = sanitize( $_POST['brand'] );
				}
			} ?>
			<label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add A')?> Brand</label>
			<input type="text" name="brand" id="brand" class="form-control" value="<?php echo $brandValue ;?>">
			<?php if( isset($_GET['edit']) ) :?>
				<a href="brands.php" class="btn btn-default">cancel</a>
			<?php endif ;?>
			<input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add')?> Brand" class="btn btn-success">
		</div>
	</form>
</div><hr>
<table class="table table-bordered table-striped table-auto table-condensed">
	<thead>
		<th></th><th>Brand</th><th></th>
	</thead>
	<tbody>
		<?php while( $brand = mysqli_fetch_assoc( $brandQry ) ) : ?>
			<tr>
				<td><a href="brands.php?edit=<?= $brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
				<td><?= $brand['brand'] ;?></td>
				<td><a href="brands.php?delete=<?= $brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
			</tr>
		<?php endwhile ; ?>
	</tbody>
</table>
<?php include 'includes/footer.php';