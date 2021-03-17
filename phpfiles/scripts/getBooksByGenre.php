<?php
require_once '../includes/Config.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
     if(isset($_POST['Genre'])){

         $conn = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
         if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
         }
        $Genre=$_POST['Genre'];



       $sql = "SELECT Image,Title FROM `Books` WHERE Genre='$Genre';" ;
        $result = $conn->query($sql)or die(mysql_error());

      if ($result->num_rows >0) {
       // output data of each row
       while($row[] = $result->fetch_assoc()) {

       $tem = $row;

       $json = json_encode($tem);


       }

      } else {
       echo "0 results";
      }
       echo $json;
      $conn->close();

    }
    else{
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
    }
  }

  ?>
