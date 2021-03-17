<?php

require_once '../includes/BankDbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['CardNo']) and
			isset($_POST['Month']) and
				isset($_POST['Year']) and
        isset($_POST['Cvv'])
        
)
		{
		//operate the data further

		$db = new BankDbOperation();

		$result = $db->completePayment( 	$_POST['CardNo'],
									$_POST['Month'],
									$_POST['Year'],
                  $_POST['Cvv']
                  
								);
		if($result == 1){
			$response['error'] = false;
			$response['message'] = "Payment successfully";
		}elseif($result == 2){
			$response['error'] = true;
			$response['message'] = "Some error occurred please try again";
		}elseif($result == 0){
			$response['error'] = true;
			$response['message'] = "Invalid Card Details";
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
