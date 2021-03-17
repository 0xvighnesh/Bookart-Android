<?php

require_once '../includes/DbOperation.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['BookISBN'])){
        $db = new DbOperation();

       
        $book = $db->getBookDetail($_POST['EmailID']);
        $response['error'] = false;
        $response['message'] = "Successful";
        $response['BookISBN'] = $book['BookISBN'];
        $response['Author'] = $book['Author'];
        $response['Title'] = $book['Title'];
        $response['Publisher'] = $book['Publisher'];
	$response['Language'] = $book['Language'];
	$response['Edition'] = $book['Edition'];
	$response['NoOfCopies'] = $book['NoOfCopies'];
       

    }else{
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
    }
}

echo json_encode($response);

