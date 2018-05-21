<?php

include_once '_config.php';

$error = null;
$warning = null;

$data = checkSession();
if($data) {
	header("location: " . $config['page']['dashboard']);
}

if (isset($_POST["register"])) {
	if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['cpassword'])) {
		$error = "Email or Password can't be empty.";
	} else {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];
		if ($password != $cpassword) {
			$error = "Passwords don't match.";
		} else {
			$sql = "SELECT id, status FROM users WHERE email = '{$email}' AND password = '{$password}'";
			if ($res = $con->query($sql)) {
				if ($res->num_rows > 0) {
					$error = "Your email adress is already in use.";
				} else {
					$sql = "INSERT INTO users (fullname, password, email) VALUES ('{$name}', '{$password}', '{$email}')";
					$con->query($sql);
					header("location: " . $config['page']['login']);
				}
			} else {
				header("location: " . $config['page']['oops']);
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>ProMan - Login</title>
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
				<a href="register.php" id="call-register" class="btn">Register</a>
			</div>
			<div class="clear"></div>
		</div>
	</div>

	<!--
		Body
	-->
	<div class="bodybar">
		<div class="container">

			<?php

				if (!is_null($error)) {
			?>
				<div class="error"><?= $error ?></div>
			<?php
				}

			?>

			<div class="bg-white pad">
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
				</div>
			</div>
		</div>
	</div>


	<?php footer($config); ?>

	<!--
		Models
	-->
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