<?php 
require_once '../core/init.php';
if( !is_logged_in()){
	header('Location: login.php');
}
include 'includes/header.php';
include 'includes/navigation.php';

?>
Admin
<?php include 'includes/footer.php';