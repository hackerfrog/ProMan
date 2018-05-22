<?php

include_once '_config.php';

$data = checkSession();
if($data == false) {
	header("location: " . $config['page']['login']);
}
$proId = null;
if (!isset($_GET['project'])) {
	header("location: " . $config['page']['dashboard']);
} else {
	$proId = $_GET['project'];
}

$error = null;

$sql = "SELECT * FROM projects WHERE id = '{$proId}' AND ( own = '{$data["id"]}' OR team LIKE '%\"{$data["id"]}\"%' )";
$proData = null;
if($res = $con->query($sql)) {
	if ($res->num_rows > 0) {
		$proData = $res->fetch_assoc();
	} else {
		header("location: " . $config['page']['dashboard']);
	}
} else {
	header("location: " . $config['page']['oops']);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>ProMan - Project : "<?= $proData['name']; ?>"</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/project.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/project.js"></script>
</head>
<body>
	<!--
		HEAD
	-->
	<div class="topbar">
		<div class="left">
			<div class="title">
				<a href="<?= $config['page']['index']; ?>">ProMan</a>
			</div>
		</div>
		<div class="right">
			<ul class="menu-h">
				<li>
					<a class="btn" id="call-setting" href="#setting.php"><?= $data['fullname']; ?> <i class="fas fa-caret-down"></i></a>
					<div id="popup-setting" class="popup" style="z-index: 100;">
						<ul class="menu-v">
							<li><a href="#">Profile</a></li>
							<li><a href="#">Settings</a></li>
							<li class="seprator"></li>
							<li><a href="<?= $config['page']['logout']; ?>" class="bg-red">Logout</a></li>
						</ul>
					</div>
				</li>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
	<div class="projectbar">
		<div class="flex-left"></div>
		<div class="flex-center">
			<div class="flex-row title"><?= $proData['name']; ?></div>
			<div id="tabs" class="flex-row tabs">
				<div id="tab-board" class="tab selected">Board</div>
				<div id="tab-convo" class="tab">Conversation</div>
				<div id="tab-activ" class="tab">Activity</div>
			</div>
		</div>
		<div class="flex-right project-options">
			<ul class="menu-h">
				<li id="call-team-setting">
					<i class="fas fa-user-friends"></i>
				</li>
			</ul>
		</div>
	</div>
	<div id="tabs-area" class="tab-area">
		<div id="tab-board" class="board-box active">
		
			<?php

				$sql = "SELECT * FROM boards WHERE project_id = '{$proId}'";
				if ($resBoard = $con->query($sql)) {
					if ($resBoard->num_rows > 0) {
						while ($row = $resBoard->fetch_assoc()) {
						?>
							<div class="board">
								<div class="name"><?= $row['name']; ?></div>
								<div id="call-newcard" class="newcard">
									+
									<input id="boardId" type="hidden" value="<?= $row['id']; ?>">
									<input id="projectId" type="hidden" value="<?= $proId; ?>">
									<input id="boardName" type="hidden" value="<?= $row['name']; ?>">
								</div>
								<div id="cards" class="cards">
									<?php

										$sql = "SELECT * FROM cards WHERE board_id = '{$row['id']}' AND project_id = '{$proId}' AND status = 'active'";
										if($resCard = $con->query($sql)) {
											if($resCard->num_rows > 0) {
												while($row = $resCard->fetch_assoc()) {
													$done = ($row['done']) ? ' done' : '';
												?>
													<div class="card<?= $done; ?>">
														<div class="title">
															<a id="call-card-box" href="#">
																<?= $row['title']; ?>
																<input id="card-id" type="hidden" value="<?= $row['id']; ?>">
															</a>

														</div>
												<?php
													if(!empty($row['body'])) {
												?>
														<div class="body"><?= $row['body']; ?></div>
												<?php
													} else {
												?>
														<div class="body empty"></div>
												<?php
													}
												?>
														<div class="foot"></div>
													</div>
												<?php
												}
											}
										} else {
											echo "Unable to connect with server.";
										}

									?>
								</div>
							</div>
						<?php
						}
					}
				} else {
					echo "Unable to connect with server.";
				}

			?>
			<div id="new-board" class="board new text-center">
				<span class="ico">+</span>
				Add New Board
			</div>
		</div>
		<div id="tab-convo" class="convo-box">
			<div class="container">
				<div class="new-post">
					<div class="type">New Post</div>
					<div class="type">Link task</div>
					<div class="type">Upload Image</div>
				</div>
			</div>
		</div>
		<div id="tab-activ" class="activ-box">
			<div class="container">
				<div class="bg-white pad">
					<?php

						$sql = "SELECT * FROM activity WHERE project_id = '{$proId}' ORDER BY created_on DESC LIMIT 50";
						if ($res = $con->query($sql)) {
							if ($res->num_rows > 0) {
								$rowIndex = 1;
								while ($row = $res->fetch_assoc()) {
									if($rowIndex > 50) {
										echo '<div class="activity text-center">...</div>';
										break;
									}
									?>
									<div class="activity"><span class="time">[<?= $row['created_on']; ?>]</span><?= $row['detail']; ?></div>
									<?php
									$rowIndex++;
								}
							} else {
								echo "No activity performed till now.";
							}
						} else {
							echo "Unable to connect with server.";
						}

					?>
				</div>
			</div>
		</div>
	</div>


	<!--
		Models
	-->
	<div id="model-newboard" class="model">
		<div id="model" class="box">
			<div class="close"></div>
			<div class="head">New Board</div>
			<div class="body">
				<form id="ajax-post">
					<input type="hidden" name="id" value="<?= $proData['id']; ?>">
					<label>Project Name</label>
					<input type="text" disabled name="proname" value="<?= $proData['name']; ?>">
					<label>Name</label>
					<input type="text" name="name" required="required">
					<div class="text-right">
						<input class="btn" type="submit" name="newboard" value="Create">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="model-newcard" class="model">
		<div id="model" class="box">
			<div class="close"></div>
			<div class="head">New Card</div>
			<div class="body">
				<form id="ajax-post">
					<input id="model-projectId" type="hidden" name="projectId">
					<input id="model-boardId" type="hidden" name="boardId">
					<label>Board Name</label>
					<input id="model-boardName" type="text" disabled name="proname" value="<?= $proData['name']; ?>">
					<label>Card Title</label>
					<input type="text" name="title" required="required">
					<label>Card Body <!--<span class="right">(Markdown Supported)</span>--></label>
					<textarea name="body"></textarea>
					<div class="text-right">
						<input class="btn" type="submit" name="newboard" value="Create">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="model-card-box" class="model">
		<div id="model" class="box">
			<div class="close"></div>
			<div id="card-title" class="head">
				<span id="call-card-done" class="check">
					<i class="fas fa-check"></i>
				</span>
				<span class="title"></span>
			</div>
			<div class="card-detail">
				<div class="left">
					<span id="proName"></span>
					<i class="fas fa-caret-right"></i>
					<span id="boardName">
						<select>
							<?php

								$sql = "SELECT id, name FROM boards WHERE project_id = '{$proId}'";
								$res = $con->query($sql);
								if($res->num_rows > 0) {
									while ($row = $res->fetch_assoc()) {
									?>
									<option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
									<?php
									}
								}

							?>
						</select>
					</span>
				</div>
				<div class="right">
					<ul class="menu-h">

						<li title="Due Date"><i class="far fa-calendar-check"></i></li>
						<li class="red" title="Delete"><i class="far fa-trash-alt"></i></li>
					<!--
						<li>&#8285;</li>
					-->
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<div id="card-body" class="body"></div>
			<div class="foot">
				<div class="newcmt">
					<form>
						<label>New Comment</label>
						<textarea name="cmt"></textarea>
					</form>
				</div>
				<div class="cmts">
					<div class="cmt"></div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>