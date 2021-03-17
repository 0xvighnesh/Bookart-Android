<?php


require_once '../includes/Config.php';
$conn = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);

if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Image,Title FROM `Books` WHERE toprated='1';";
$result = $conn->query($sql);

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
?>
