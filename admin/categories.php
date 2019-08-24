<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if( !is_logged_in()){
	login_error_redirect();
}
include 'includes/header.php';
include 'includes/navigation.php';
$sqlCat  = "SELECT * FROM categories WHERE parent = 0";
$qryCat = $db->query( $sqlCat );
$errors = array();
$categoryEdit = $categoryDel = $parentPost = $categoryPost = '';

//edit category
if( isset($_GET['edit']) && !empty($_GET['edit']) ){
	$editId = (int)$_GET['edit'];
	$editId = sanitize( $editId );
	$sqlEdit = "SELECT * FROM categories WHERE id = '$editId'";
	$qryEdit = $db->query( $sqlEdit );
	$categoryEdit = mysqli_fetch_assoc( $qryEdit );
}
//delete category
if( isset($_GET['delete']) && !empty($_GET['delete']) ){
	$deleteId = (int)$_GET['delete'];
	$deleteId = sanitize( $deleteId );
	$sql = "SELECT * FROM categories WHERE id = '$deleteId'";
	$qry = $db->query( $sql );
	$categoryDel = mysqli_fetch_assoc( $qry );
	if ( $categoryDel['parent'] == 0) {
		$sqlD = "DELETE FROM categories WHERE parent = '$deleteId'";
		$db->query( $sqlD );
	}
	$sqlDel = "DELETE FROM categories WHERE id = '$deleteId'";
	$qryDel = $db->query( $sqlDel );
	header('Location: categories.php');
}
if( isset($_POST) && !empty($_POST) ){
	 $parentPost = sanitize($_POST['parent']);
	 $categoryPost = sanitize($_POST['category']);

	//if category left empty
	if ( $categoryPost == '' ) {
		 $errors[] .= 'The category cannot be left blank';
	}

	//if category already present
	$sql = "SELECT * FROM categories WHERE category = '$categoryPost' AND parent = '$parentPost' ";
	if( isset($_GET['edit']) ) {
		$sql = "SELECT * FROM categories WHERE category = '$categoryPost' AND parent = '$parentPost' AND id != '$editId' ";
	}
	$result = $db->query( $sql );
	$count = mysqli_num_rows( $result );
	
	if( $count > 0 ) {
		$errors[] .= $categoryPost.' is already present. Enter different one...';
	}
	if(!empty( $errors ) ){
		$display = display_errors( $errors );?>
		<script>
			jQuery('document').ready(function(){
				jQuery('#errors').html('<?=$display;?>');
			});
		</script>
	
	<?php }else{
		//add category
		$sqlPost = "INSERT INTO categories(category,parent) VALUES('$categoryPost','$parentPost')";
		if( isset($_GET['edit']) ) {
			$sqlPost = "UPDATE categories SET category = '$categoryPost' , parent = '$parentPost' WHERE id = '$editId' ";
		}
		$db->query( $sqlPost );
		header('Location: categories.php');
	} 
}
$categoryValue = '';
 $parentValue = 0;
if( isset($_GET['edit']) ){
	$categoryValue = $categoryEdit['category'];
	$parentValue =  $categoryEdit['parent'];
}else {
	if (isset($_POST)) {
		$categoryValue = $categoryPost;
		$parentValue = $parentPost;	
	}
}
?>
<h2 class="text-center">Categories</h2><hr>
<div class="row">
	<div class="col-md-6">
		<form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$editId:'')?>" method="post">
		    <legend><?=((isset($_GET['edit']))?'Edit':'Add A')?> Category</legend>
		    <div id="errors"></div>
			<div class="form-group">
				<label for="parent">Parent</label>
				<select class="form-control" id="parent" name="parent">
					<option value="0"<?=(($parentValue==0)?' selected="selected"':'')?>>Parent</option>
					<?php while( $parent = mysqli_fetch_assoc( $qryCat ) ) :?>
						<option value="<?=$parent['id']?>"<?=(($parentValue==$parent['id'])?' selected="selected"':'')?> ><?=$parent['category']?></option>
					<?php endwhile ;?>
				</select>
			</div>
			<div class="form-group">
				<label for="category">Category</label>
				<input type="text" name="category" class="form-control" id="category" value="<?=$categoryValue?>">
			</div>
			<div class="form-group">
				<input type="submit" name="submit" class="btn btn-success" value="<?=((isset($_GET['edit']))?'Edit':'Add')?> Category">
			</div>
		</form>
	</div>

	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>
				<th>Category</th><th>Parent</th><th></th>
			</thead>
			<tbody>
			    <?php 
					$sqlCat = "SELECT * FROM categories WHERE parent = 0";
					$qryCat = $db->query( $sqlCat );

				    while( $parent = mysqli_fetch_assoc( $qryCat ) ) :
						$parentId = (int)$parent['id'];
						$sqlChild = "SELECT * FROM categories WHERE parent ='$parentId' ";
						$qryChild = $db->query( $sqlChild );
					    ?>
						<tr class="bg-primary">
							<td><?= $parent['category'] ?></td>
							<td>Parent</td>
							<td>
								<a href="categories.php?edit=<?=$parent['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="categories.php?delete=<?=$parent['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
							</td>
						</tr>
				        <?php while( $child = mysqli_fetch_assoc( $qryChild ) ) : ?>
				        	<tr class="bg-info">
							<td><?= $child['category'] ?></td>
							<td><?=$parent['category'] ?></td>
							<td>
								<a href="categories.php?edit=<?=$child['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="categories.php?delete=<?=$child['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
							</td>
						    </tr>
				        <?php endwhile ;?>
			    	<?php endwhile ;?>
			</tbody>
		</table>
	</div>
	
</div>

<?php include 'includes/footer.php';