<?php

include_once '_config.php';

$data = checkSession();
if($data == false) {
	header("location: " . $config['page']['login']);
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>ProMan - Dashboard</title>
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
				<a href="#setting.php" id="call-setting" class="btn"><?= $data['fullname']; ?> &#9660;</a>
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
			<div class="head">
				Projects
				<a href="newproject.php" id="call-newproject" class="btn right">+</a>
				<div class="clear"></div>
			</div>
			<div id="projects" class="projects">
				<?php

					$sql = "SELECT * FROM projects WHERE own = '{$data['id']}' OR team LIKE '%\"{$data['id']}\"%'";
					if ($res = $con->query($sql)) {
						if ($res->num_rows > 0) {
							while ($row = $res->fetch_assoc()) {
								$sql = "SELECT done, COUNT(*) AS num FROM cards WHERE project_id = '{$row['id']}' GROUP BY done";
								$cOpen = 0;
								$cClose = 0;
								if($resCount = $con->query($sql)) {
									if($resCount->num_rows == 2) {
										$rowCount = $resCount->fetch_assoc();
										$cOpen = $rowCount['num'];
										$rowCount = $resCount->fetch_assoc();
										$cClose = $rowCount['num'];
									} elseif ($resCount->num_rows == 1) {
										$rowCount = $resCount->fetch_assoc();
										if ($rowCount['done'] == '1') {
											$cClose = $rowCount['num'];
										} else {
											$cOpen = $rowCount['num'];
										}
									}
									
								}
				?>
					<div class="tile">
						<a href="<?= $config['page']['project'] . '?project=' . $row['id']; ?>">
							<div class="head"><?= $row['name']; ?></div>
						</a>
						<div class="body"><?= (empty($row['detail'])) ? 'No detail' : $row['detail']; ?></div>
						<div class="foot text-right">Open - <?= $cOpen; ?> &bull; Closed - <?= $cClose; ?></div>
					</div>

				<?php
							}
						} else {
				?>
					<div class="message text-center pad"><i>I'm not running away from work, I'm too lazy to create project.</i></div>
				<?php
						}
					} else {
						header("location: " . $config['page']['oops']);
					}

				?>
			</div>
		</div>
	</div>

	<?php footer($config); ?>

	<!--
		Models
	-->
	<div id="model-newproject" class="model">
		<div id="model" class="box">
			<div class="close"></div>
			<div class="head">New Project</div>
			<div class="body">
				<form id="ajax-post" action="<?= $config['page']['newproject']; ?>" method="post">
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
					<div class="text-right">
						<input class="btn" type="submit" name="create" value="Create Project">
					</div>
				</form>
			</div>
		</div>
	</div>


</body>
</html>