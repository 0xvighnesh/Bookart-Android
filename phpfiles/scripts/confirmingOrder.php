<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['BookISBN']) and
			isset($_POST['EmailID']) and
				isset($_POST['startTime']) and
        isset($_POST['endTime']) 
       
)
		{
		//operate the data further

		$db = new DbOperation();

		$result = $db->enterOrderDetails( 	$_POST['BookISBN'],
									$_POST['EmailID'],
						$_POST['startTime'],$_POST['endTime']
                  
								);
		if($result == 1){
			$response['error'] = false;
			$response['message'] = "Order Confirmed";
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

