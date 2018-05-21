<?php

include_once '../_config.php';

$data = checkSession();
if($data == false) {
	header("location: " . $config['page']['login']);
}


if (isset($_POST['ajax-changeboard'])) {
	if (empty($_POST['cardId']) || empty($_POST['newBoardId'])) {
		$return['status'] = "error";
		$return['message'] = "Invalid request.";
		echo json_encode($return);
		exit();
	} else {
		$cardId = $_POST['cardId'];
		$boardId = $_POST['newBoardId'];
		$sql = "SELECT * FROM cards WHERE id = '{$cardId}' AND status = 'active'";
		if ($resCard = $con->query($sql)) {
			if ($resCard->num_rows > 0) {
				$rowCard = $resCard->fetch_assoc();
				$sql = "SELECT * FROM projects WHERE id = '{$rowCard['project_id']}' AND ( own = '{$data['id']}' OR team LIKE '%\"{$data['id']}\"%' )";
				if ($resPro = $con->query($sql)) {
					if ($resPro->num_rows > 0) {
						$rowPro = $resPro->fetch_assoc();
						$projectId = $rowPro['id'];
						$sql = "UPDATE cards SET board_id = '{$boardId}' WHERE id = '{$cardId}'";
						if($con->query($sql)) {
							$return['status'] = 'success';
							$return['message'] = 'Card board is changed.';
							$activityDetail = 'Card <b>#'.$cardId.'</b> is tranferd to Board <b>#'.$boardId.'</b> by <a id="user" href="'.$config['page']['profile'].'?id='.$data['id'].'"><i id="'.$data['id'].'"></i>'.$data['fullname'].'</a>';
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
						$return['message'] = 'You are not authorized in this project.';
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
				$return['status'] = "error";
				$return['message'] = "Invalid request.";
				echo json_encode($return);
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