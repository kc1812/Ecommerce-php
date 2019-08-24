<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
if(!is_logged_in()){
	login_error_redirect();
}
include 'includes/header.php';
$hashed = substr($user_data['password'], 0, 60);
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($confirm,PASSWORD_DEFAULT);
$user_id = $user_data['id'];

$errors = array();
?>
<style>
	body{
		background-image: url("/ecom/images/headerlogo/background.png");
		background-size: 100vw 100vh;
		background-attachment: fixed;
	}
</style>
<div id="login-form">
	<div>
		<?php 
		if($_POST){
			if( empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
			$errors[] = 'You must provide all fields.';
			}
			
			if(strlen($password) < 6){
				$errors[] = 'Password must be atleast 6 characters.';
			}
			if($password != $confirm){
				$errors[] = 'New password and Confirm does not match';

			}
			if(!password_verify($old_password, $hashed)){
				$errors[] = 'Your old password does not match our records. Please try again';
			}
			if(!empty($errors)){
				echo display_errors($errors);
			}else {
				$db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
				$_SESSION['success_flash'] = 'Your password has been changed';
				header('Location: index.php'); 
			}
			}
		?>
	</div>
	<h2 class="text-center">Change Password</h2>
	<form action="change_password.php" method="post">
		<div class="form-group">
			<label for="old_password">Old Password:</label>
			<input type="password" name="old_password" id="old_password" class="form-control" value="<?= $old_password;?>">
		</div>
		<div class="form-group">
			<label for="password">New Password:</label>
			<input type="password" name="password" id="password" class="form-control" value="<?= $password;?>">
		</div>
		<div class="form-group">
			<label for="confirm">Confirm New Password:</label>
			<input type="password" name="confirm" id="confirm" class="form-control" value="<?= $confirm;?>">
		</div>
		<div class="form-group">
			<a href="index.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="Change Password" class="btn btn-success">
		</div>
	</form>
	<p class="text-right"><a href="/ecom/index.php" alt="home">Visit Site</a></p>
</div> 

<?php require_once 'includes/footer.php';?>
