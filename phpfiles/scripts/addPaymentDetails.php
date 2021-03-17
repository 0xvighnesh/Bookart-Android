<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['EmailID']) and
			
)
		{
		//operate the data further

		$db = new DbOperation();

		$result = $db->addPaymentDetails( 	$_POST['EmailID']
								);
		if($result == 1){
			$response['error'] = false;
			$response['message'] = "User registered successfully";
		}elseif($result == 2){
			$response['error'] = true;
			$response['message'] = "Some error occurred please try again";
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
