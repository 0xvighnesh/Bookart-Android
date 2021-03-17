<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['EmailID'])
			
)
		{
		//operate the data further

		$db = new DbOperation();

		$result = $db->printReturnMessage( $_POST['EmailID']	);
		if($result == 1){
			$response['error'] = false;
			$response['message'] = "Account is valid";
		}elseif($result == 0){
			$response['error'] = false;
			$response['message'] = "Expired";
		}elseif($result == 2){
			$response['error'] = false;
			$response['message'] = "Not needed";
		}

	}else{
		$response['error'] = true;
		$response['message'] = "Required fields are missing";
	}
}else{
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}

echo json_encode($response);
