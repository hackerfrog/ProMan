<?php

include_once '../_config.php';

$data = checkSession();
if($data == false) {
	header("location: " . $config['page']['login']);
}

if (isset($_POST['ajax-carddata'])) {
	if (empty($_POST['cardId'])) {
		$return['status'] = "error";
		$return['message'] = "Card title can't be empty";
		echo json_encode($return);
		exit();
	} else {
		$cardId = $_POST['cardId'];
		$sql = "SELECT * FROM cards WHERE id = '{$cardId}'";
		if ($resCard = $con->query($sql)) {
			if ($resCard->num_rows) {
				$rowCard = $resCard->fetch_assoc();
				$sql = "SELECT * FROM projects WHERE id = '{$rowCard['project_id']}' AND ( own = '{$data['id']}' OR team LIKE '%\"{$data['id']}\"%' )";
				if ($resPro = $con->query($sql)) {
					if ($resPro->num_rows > 0) {
						$rowPro = $resPro->fetch_assoc();
						$sql = "SELECT * FROM boards WHERE id = '{$rowCard['board_id']}'";
						if($resBoard = $con->query($sql)) {
							if($resBoard->num_rows > 0) {
								$rowBoard = $resBoard->fetch_assoc();
								$return['status'] = 'success';
								$return['data'] = $rowCard;
								$return['project'] = $rowPro;
								$return['board'] = $rowBoard;
								echo json_encode($return);
							} else {
								$return['status'] = 'error';
								$return['message'] = 'Board is unavilable.';
								echo json_encode($return);
								exit();
							}
						} else {
							$return['status'] = 'error';
							$return['message'] = 'Unable to connect with server.';
							echo json_encode($return);
							exit();
						}
						
						exit();
					} else {
						$return['status'] = 'error';
						$return['message'] = 'You are not allowed in this project.';
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
				$return['message'] = 'No card belong with given card id.';
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