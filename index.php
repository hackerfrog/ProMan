<?php

include_once '_config.php';

$data = checkSession();
if($data) {
	header("location: " . $config['page']['dashboard']);
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>ProMan</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/index.js"></script>
</head>
<body>
	<!--
		Head
	-->
	<div class="topbar">
		<div class="container">
			<div class="left">
				<h2 class="site-name-big">
					<a href="<?= $config['page']['index']; ?>">ProMan</a>
				</h2>
			</div>
			<div class="right">
				<a href="login.php" id="call-login" class="btn">Login</a>
				<a href="register.php" id="call-register" class="btn">Register</a>
			</div>
			<div class="clear"></div>
		</div>
	</div>

	<!--
		Body
	-->
	<div class="bodybar intro">
		<div class="container">
			<div class="text-center name">Pro&bull;Man</div>
			<div class="text-center name-explain">&mdash; "Project Manager"</div>
		</div>
	</div>


	<?php footer($config); ?>

	<!--
		Models
	-->
	<div id="model-login" class="model">
		<div id="model" class="box">
			<div class="close"></div>
			<div class="head">Login</div>
			<div class="body">
				<form action="<?= $config['page']['login']; ?>" method="post">
					<label>Email</label>
					<input type="email" name="email">
					<label>Password</label>
					<input type="password" name="password">
					<div class="text-right">
						<input class="btn" type="submit" name="login" value="Login">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="model-register" class="model">
		<div id="model" class="box">
			<div class="close"></div>
			<div class="head">Register</div>
			<div class="body">
				<form action="<?= $config['page']['register']; ?>" method="post">
					<label>Full Name</label>
					<input type="text" name="name">
					<label>Email</label>
					<input type="email" name="email">
					<label>Password</label>
					<input type="password" name="password">
					<label>Confirm Password</label>
					<input type="password" name="cpassword">
					<div class="text-right">
						<input class="btn" type="submit" name="register" value="Register">
					</div>
				</form>
			<div>
		</div>
	</div>
</body>
</html>