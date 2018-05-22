<?php

include_once '../_config.php';

$data = checkSession();
if($data == false) {
	header("location: " . $config['page']['login']);
}
if (isset($_POST['ajax-newboard'])) {
	if (empty($_POST['name']) || empty($_POST['id'])) {
		$return['status'] = "error";
		$return['message'] = "Board name can't be empty";
		echo json_encode($return);
		exit();
	} else {
		$proId = $_POST['id'];
		$name = $_POST['name'];
		$sql = "SELECT * FROM projects WHERE id = '{$proId}' AND (own = '{$data['id']}' OR team LIKE '%\"{$data['id']}\"%')";
		if ($res = $con->query($sql)) {
			if ($res->num_rows > 0) {
				$sql = "INSERT INTO boards (name, project_id) VALUES ('{$name}', '{$proId}')";
				if ($con->query($sql)) {
					$boardId = $con->insert_id;
					$return['status'] = 'success';
					$return['result'] = '
					<div class="board">
						<div class="name">' . $name . '</div>
						<div id="call-newcard" class="newcard">
							+
							<input id="boardId" type="hidden" value="' . $boardId . '">
							<input id="projectId" type="hidden" value="' . $proId . '">
							<input id="boardName" type="hidden" value="' . $name . '">
						</div>
						<div class="cards">
							
						</div>
					</div>';
					$activityDetail = '<b>'.$name.'</b> board created by <a id="user" href="'.$config['page']['profile'].'?id='.$data['id'].'"><i id="'.$data['id'].'"></i>'.$data['fullname'].'</a>';
					$sql = "INSERT INTO activity (project_id, detail, performed_by) VALUES ('{$proId}', '{$activityDetail}', '{$data['id']}')";
					$con->query($sql);
					echo json_encode($return);
					exit();
				} else {
					$return['status'] = 'error';
					$return['message'] = 'Unable to connect with server.';
					echo json_encode($return);
					exit();
				}
			} else {
				$return['status'] = 'error';
				$return['message'] = 'You are not allowed to add new board to this project.';
				echo json_encode($return);
				exit();
			}
		} else {
			$return['status'] = 'error';
			$return['message'] = 'Unable to connect with server.';
			echo json_encode($return);
			exit();
		}
	}
}

?>