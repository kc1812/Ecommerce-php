<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
$parentId = (int)$_POST['parentId'];
$selected = sanitize($_POST['selected']);
$qryChild = $db->query( "SELECT * FROM categories WHERE parent = '$parentId' ORDER BY category " );  
ob_start(); ?>
<option value=""></option>
<?php while( $child = mysqli_fetch_assoc( $qryChild )) :?>
	<option value="<?=$child['id']?>" <?=(( $selected == $child['id'])?' selected':'')?>><?= $child['category']?></option>
<?php endwhile ;
echo ob_get_clean();?>