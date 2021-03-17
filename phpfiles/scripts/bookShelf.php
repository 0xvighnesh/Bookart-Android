<?php
require_once '../includes/Config.php';
 

         
         $conn = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
         if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
         }
        
        	if(
		isset($_POST['EmailID'])){
       $EmailID= $_POST['EmailID'];
      
       
         $sql= "SELECT Books.Title,Orders.OrderID FROM Books INNER JOIN Orders ON Books.BookISBN=Orders.BookISBN WHERE EmailID='";
        $sql.=$EmailID;
        $sql.="' AND OrderID IN (SELECT OrderID FROM Delivery WHERE status='1'      )";
      
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
		else    {
		    echo "Parameter error";
		}
		
     

    

  ?>
