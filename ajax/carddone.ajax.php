<?php

include_once '../_config.php';

$data = checkSession();
if($data == false) {
	header("location: " . $config['page']['login']);
}


if (isset($_POST['ajax-carddone'])) {
	if (empty($_POST['cardId'])) {
		$return['status'] = "error";
		$return['message'] = "Invalid request.";
		echo json_encode($return);
		exit();
	} else {
		$cardId = $_POST['cardId'];
		$sql = "SELECT * FROM cards WHERE id = '{$cardId}'";
		if ($resCard = $con->query($sql)) {
			if ($resCard->num_rows > 0) {
				$rowCard = $resCard->fetch_assoc();
				$proId = $rowCard['project_id'];
				$sql = "SELECT * FROM projects WHERE id = '{$proId}' AND ( own = '{$data['id']}' OR team LIKE '%\"{$data['id']}\"%' )";
				if ($resPro = $con->query($sql)) {
					if ($resPro->num_rows > 0) {
						$rowPro = $resPro->fetch_assoc();
						$sql = "UPDATE cards SET done = !done WHERE id = '{$cardId}'";
						if ($con->query($sql)) {
							$maked = ($rowCard['done']) ? 'removed from' : 'marked as';
							$return['status'] = 'success';
							$return['message'] = 'Card is maked as Checked.';
							$activityDetail = 'Card <b>#'.$cardId.'</b> '.$maked.' <i class="fas fa-check"></i> by <a id="user" href="'.$config['page']['profile'].'?id='.$data['id'].'"><i id="'.$data['id'].'"></i>'.$data['fullname'].'</a>';
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
						$return['message'] = 'You are not authorized to change card status.';
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