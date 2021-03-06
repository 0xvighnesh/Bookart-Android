<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['OrderID'])
       
)
		{
		//operate the data further

		$db = new DbOperation();

		$result = $db->updateDeliveryStatus( $_POST['OrderID']   );
		if($result == 0){
			$response['error'] = false;
			$response['message'] = "Status Updated";
		}elseif($result == 1){
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

