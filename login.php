<?php

include_once '_config.php';

$error = null;
$warning = null;

$data = checkSession();
if($data) {
	header("location: " . $config['page']['dashboard']);
}

if (isset($_POST["login"])) {
	if (empty($_POST['email']) || empty($_POST['password'])) {
		$error = "Email or Password can't be empty.";
	} else {
		$email = $_POST['email'];
		$password = $_POST['password'];
		$sql = "SELECT id, hash, status FROM users WHERE email = '{$email}' AND password = '{$password}'";
		if ($res = $con->query($sql)) {
			if ($res->num_rows > 0) {
				$row = $res->fetch_assoc();
				$id = $row['id'];
				$status = $row['status'];
				if ($status != 1) {
					header("location: " . $config['page']['reactivate']);
				} else {
					$hash = md5(time());
					$sql = "UPDATE users SET hash = '{$hash}' WHERE id = '{$id}'";
					if ($con->query($sql)) {
						$_SESSION['id'] = $id;
						$_SESSION['hash'] = $hash;
						header("location: " . $config['page']['dashboard']);
					} else {
						header("location: " . $config['page']['oops']);
					}
				}
			} else {
				$error = "Wrong email or password.";
			}
		} else {
			header("location: " . $config['page']['oops']);
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
				<div class="head">Login</div>
				<div class="body">
					<form action="<?= $config['page']['login']; ?>" method="post">
						<label>Email</label>
						<input type="email" name="email">
						<label>Password</label>
						<input type="password" name="password">
						<div class="text-center">
							<input class="btn" type="submit" name="login" value="Login">
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