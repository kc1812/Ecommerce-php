<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecom/core/init.php';
include 'includes/header.php';
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);

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
			if( empty($_POST['email']) || empty($_POST['password'])){
			$errors[] = 'You must provide email and password.';
			}
			if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
				$errors[] = 'You must enter a valid email';
			}
			if(strlen($password) < 6){
				$errors[] = 'Password must be atleast 6 characters.';
			}
			$qryUser = $db->query("SELECT * FROM users WHERE email = '$email'");
			$user = mysqli_fetch_assoc($qryUser);
			$userCount = mysqli_num_rows($qryUser);
			if($userCount < 1){
				$errors[] = 'That email doesn\'t exist in our database';
			}
			$hash = substr($user['password'], 0, 60);
			
			if(!password_verify($password, $hash)){
				$errors[] = 'The password doesnot match our records. Please try again';
			}
			if(!empty($errors)){
				echo display_errors($errors);
			}else {
				$user_id = $user['id'];
				login($user_id);
			}
			}
		?>
	</div>
	<h2 class="text-center">Login</h2>
	<form action="login.php" method="post">
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control" value="<?= $email;?>">
		</div>
		<div class="form-group">
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" class="form-control" value="<?= $password;?>">
		</div>
		<div class="form-group">
			<input type="submit" value="Login" class="btn btn-success">
		</div>
	</form>
	<p class="text-right"><a href="/ecom/index.php" alt="home">Visit Site</a></p>
</div> 

<?php require_once 'includes/footer.php';?>
