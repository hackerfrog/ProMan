<?php

include_once '_config.php';

$data = checkSession();
if($data == false) {
	header("location: " . $config['page']['login']);
}

if (isset($_POST['ajax-create'])) {
	if (empty($_POST['name']) || empty($_POST['type'])) {
		$return['status'] = 'error';
		$return['message'] = "Project name can't be empty.";
		echo json_encode($return);
		exit();
	} else {
		$name = $_POST['name'];
		$detail = $_POST['detail'];
		$type = $_POST['type'];
		$team = json_encode(array($data['id']));
		if($type == 'pub' || $type == 'pro' || $type == 'pri') {
			$sql = "INSERT INTO projects (name, detail, own, type, team) VALUES ('{$name}', '{$detail}', '{$data['id']}', '{$type}', '{$team}')";
			if ($res = $con->query($sql)) {
				$return['status'] = 'success';
				$proId = $con->insert_id;
				$sql = "SELECT * FROM projects WHERE id = '{$con->insert_id}'";
				if($res = $con->query($sql)) {
					$row = $res->fetch_assoc();
					$detail = $row['detail'];
					if (empty($row['detail'])) {
						$detail = 'No detail';
					}
					$return['result'] = '
					<div class="tile">
						<a href="' . $config['page']['project'] . '?project=' . $proId . '">
							<div class="head">' . $row['name'] . '</div>
						</a>
						<div class="body">' . $detail . '</div>
						<div class="foot text-right">Open - X &bull; Closed - X</div>
					</div>';
					$activityDetail = 'Project created by <a id="user" href="'.$config['page']['profile'].'?id='.$data['id'].'"><i id="'.$data['id'].'"></i>'.$data['fullname'].'</a>';
					$sql = "INSERT INTO activity (project_id, detail, performed_by) VALUES ('{$proId}', '{$activityDetail}', '{$data['id']}')";
					$con->query($sql);
					echo json_encode($return);
					exit();
				} else {
					$return['status'] = 'error';
					$return['message'] = "Unable to connect with server.";
				}
			} else {
				$return['status'] = 'error';
				$return['message'] = "Unable to connect with server.";
			}
		} else {
			$return['status'] = 'error';
			$return['message'] = "Invalid project type selected.";
			echo json_encode($return);
			exit();
		}
	}
}

$error = null;
if (isset($_POST['create'])) {
	if (empty($_POST['name']) || empty($_POST['type'])) {
		$error = "Project name can't be empty";
	} else {
		$name = $_POST['name'];
		$detail = $_POST['detail'];
		$type = $_POST['type'];
		$team = json_encode(array($data['id']));
		if($type == 'pub' || $type == 'pro' || $type == 'pri') {
			$sql = "INSERT INTO projects (name, detail, own, type, team) VALUES ('{$name}', '{$detail}', '{$data['id']}', '{$type}', '{$team}')";
			if ($res = $con->query($sql)) {
				header("location: " . $config['page']['project'] . '?project=' . $con->insert_id);
			} else {
				header("location: " . $config['page']['oops']);
			}
		} else {
			$error = "Invalid project type selected.";
		}
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>ProMan - New Project</title>
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
					<a class="relative" href="<?= $config['page']['index']; ?>">ProMan</a>
				</h2>
			</div>
			<div class="right">
				<a href="setting.php" id="call-setting" class="btn"><?= $data['fullname']; ?> &#9660;</a>
				<div id="popup-setting" class="popup">
					<ul class="menu-v">
						<li><a href="#">Profile</a></li>
						<li><a href="#">Settings</a></li>
						<li class="seprator"></li>
						<li><a href="<?= $config['page']['logout']; ?>" class="bg-red">Logout</a></li>
					</ul>
				</div>
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
				<div class="head">New Project</div>
				<div class="body">
					<form action="<?= $config['page']['newproject']; ?>" method="post">
						<label>Project Name</label>
						<input type="text" name="name" required="required">
						<label>Project Detail</label>
						<textarea name="detail"></textarea>
						<label>Project Type</label>
						<select name="type">
							<option value="pub">Public</option>
							<option value="pro">Protected</option>
							<option value="pri">Private</option>
						</select>
						<div class="text-center">
							<input class="btn" type="submit" name="create" value="Create Project">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</body>
</html>