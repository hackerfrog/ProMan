<?php

	include_once '_subpage.php';

	$config = array(
		'database' => array(
			'host' => 'localhost',
			'username' => 'root',
			'password' => '',
			'database' => 'proman'
		),
		'page' => array(
			// Site Pages
			'index' => 'index.php',
			'login' => 'login.php',
			'reactive' => 'reactive.php',
			'register' => 'register.php',
			'dashboard' => 'dashboard.php',
			'newproject' => 'newproject.php',
			'project' => 'project.php',
			'profile' => 'profile.php',
			'logout' => 'logout.php',
			// Error Pages
			'oops' => 'oops.html'
		),
	);

	function connect() {
		global $config;
		return mysqli_connect($config['database']['host'], $config['database']['username'], $config['database']['password'], $config['database']['database']);
	}

	$con = connect();
	session_start();

	function checkSession() {
		global $con;
		if(isset($_SESSION['id'])) {
			$id = $_SESSION['id'];
			$hash = $_SESSION['hash'];
			$sql = "SELECT id, fullname, status, hash FROM users WHERE id = '{$id}' AND hash = '{$hash}'";
			if ($res = $con->query($sql)) {
				if ($res->num_rows > 0) {
					return $res->fetch_assoc();
				} else {
					return false;
				}
			} else {
				haeder("location: " . $config['page']['oops']);
			}
		} else {
			return false;
		}
	}
?>