<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['OrderID'])){
        $db = new DbOperation();

       
        $order = $db->getOrderDetail($_POST['OrderID']);
        $response['error'] = false;
        $response['message'] = "Successful";
        $response['deliveryDate'] = $order['deliveryDate'];
        $response['deliverystartTime'] = $order['deliverystartTime'];
        $response['deliveryendTime'] = $order['deliveryendTime'];
        
       

    }else{
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
    }
}

echo json_encode($response);
