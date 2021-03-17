<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['EmailID']) and
			isset($_POST['Name']) and
				isset($_POST['Cust_Password']) and
        isset($_POST['Address']) and
        isset($_POST['PhoneNo'])
)
		{
		//operate the data further

		$db = new DbOperation();

		$result = $db->createUser( 	$_POST['EmailID'],
									$_POST['Name'],
									$_POST['Cust_Password'],
                  $_POST['Address'],
                  $_POST['PhoneNo']
								);
		if($result == 1){
			$response['error'] = false;
			$response['message'] = "User registered successfully";
		}elseif($result == 2){
			$response['error'] = true;
			$response['message'] = "Some error occurred please try again";
		}elseif($result == 0){
			$response['error'] = true;
			$response['message'] = "It seems you are already registered!!";
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
