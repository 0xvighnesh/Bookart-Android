<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['EmailID']) and isset($_POST['Cust_Password'])){
        $db = new DbOperation();

        if($db->userLogin($_POST['EmailID'], $_POST['Cust_Password'])){
            $user = $db->getUserByUsername($_POST['EmailID']);
            $response['error'] = false;
            $response['EmailID'] = $user['EmailID'];
            $response['Name'] = $user['Name'];
            $response['Cust_Password'] = $user['Cust_Password'];
            $response['Address'] = $user['Address'];
            $response['PhoneNo'] = $user['PhoneNo'];
        }else{
            $response['error'] = true;
            $response['message'] = "Invalid email id or password";
        }

    }else{
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
    }
}

echo json_encode($response);
