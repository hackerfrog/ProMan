<?php

include_once '../_config.php';

$data = checkSession();
if($data == false) {
	header("location: " . $config['page']['login']);
}
if(isset($_POST['ajax-newcard'])) {
	if (empty($_POST['projectId']) || empty($_POST['boardId']) || empty($_POST['title'])) {
		$return['status'] = "error";
		$return['message'] = "Card title can't be empty";
	} else {
		$projectId = $_POST['projectId'];
		$boardId = $_POST['boardId'];
		$title = $_POST['title'];
		$body = $_POST['body'];
		$sql = "SELECT * FROM projects WHERE id = '{$projectId}' AND (own = '{$data['id']}' OR team LIKE '%\"{$data['id']}\"%')";
		if ($res = $con->query($sql)) {
			if ($res->num_rows > 0) {
				$sql = "SELECT * FROM boards  WHERE id = '{$boardId}' AND project_id = '{$projectId}'";
				if($res = $con->query($sql)) {
					if($res->num_rows > 0) {
						$row = $res->fetch_assoc();
						$boardName = $row['name'];
						$sql = "INSERT INTO cards (title, body, board_id, project_id, created_by) VALUES ('{$title}', '{$body}', '{$boardId}', '{$projectId}', '{$data['id']}')";
						if($con->query($sql)) {
							$cardId = $con->insert_id;
							$return['status'] = 'success';
							$return['result'] = '
							<div class="card">
								<div class="title">' . $title . '</div>';
							if(!empty($body)) {
								$return['result'] .= '<div class="body">' . $body . '</div>';
							}
							$return['result'] .= '<div class="foot"></div>
							</div>';
							$activityDetail = 'Card <b>#'.$cardId.'</b> created under <b>'.$boardName.'</b> by <a id="user" href="'.$config['page']['profile'].'?id='.$data['id'].'"><i id="'.$data['id'].'"></i>'.$data['fullname'].'</a>';
							$sql = "INSERT INTO activity (project_id, detail, performed_by) VALUES ('{$projectId}', '{$activityDetail}', '{$data['id']}')";
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
						$return['message'] = 'Invalid board or project.';
						echo json_encode($return);
						exit();
					}
				} else {
					$return['status'] = 'error';
					$return['message'] = 'Unable to connect with server.';
					echo json_encode($return);
					exit();
				}
			} else {
				$return['status'] = 'error';
				$return['message'] = 'You are not authorized to add card in this project.';
				echo json_encode($return);
				exit();
			}
		}
	}
} else {
	$return['status'] = 'success';
	$return['message'] = 'Else.';
	echo json_encode($return);
	exit();
}

?>