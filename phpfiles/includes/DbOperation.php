<?php

	class DbOperation{

		private $con;

		function __construct(){

			require_once dirname(__FILE__).'/DB_Connect.php';

			$db = new DB_Connect();

			$this->con = $db->connect();

		}

		/*CRUD -> C -> CREATE */

		public function createUser($EmailID,$Name,$Cust_Password,$Address,$PhoneNo){
		    if($this->isUserExist($EmailID)){
				return 0;
			}else{

				$password = md5($Cust_Password);
				$stmt = $this->con->prepare("INSERT INTO `Customer` (`EmailID`, `Name`, `Cust_Password`, `Address`, `PhoneNo`,`BillNO`) VALUES  (?, ?, ?, ?,?,0);");
				$stmt->bind_param("sssss",$EmailID,$Name,$password,$Address,$PhoneNo);
                
				if($stmt->execute()){
					return 1;
				}else{
					return 2;
				}

		}

		}
		
		public function addPaymentDetails($EmailID) {
		    $stmt = $this->con->prepare("INSERT INTO `Payment` (`BillNO`, `PaymentDate`, `renewDate`, `EmailID`) VALUES (NULL, CURRENT_TIMESTAMP, DATE_ADD(PaymentDate, INTERVAL 1 YEAR), ?);");
				$stmt->bind_param("s",$EmailID);
				
				 $stmt1 = $this->con->prepare("UPDATE Customer SET BillNO = (SELECT BillNO FROM Payment WHERE EmailID=?) WHERE EmailID=?");
				$stmt1->bind_param("ss",$EmailID,$EmailID);
				 
				if($stmt->execute() && $stmt1->execute()){
					return 1;
				}else{
					return 2;
				}
		    
		}
		
		public function userLogin($EmailID, $Cust_Password){
            $password = md5($Cust_Password);
            $stmt = $this->con->prepare("SELECT EmailID FROM `Customer` WHERE EmailID=? AND Cust_Password=?;");
            $stmt->bind_param("ss",$EmailID,$password);
            $stmt->execute();
            $stmt->store_result(); 
            return $stmt->num_rows > 0; 
        }
 
        public function getUserByUsername($EmailID){
            $stmt = $this->con->prepare("SELECT * FROM `Customer` WHERE EmailID = ?");
            $stmt->bind_param("s",$EmailID);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        
        public function getBookDetail($BookISBN) {
            $stmt = $this->con->prepare("SELECT BookISBN,Author,Title,Publisher,Language,Edition FROM `Books` WHERE BookISBN = ?");
            $stmt->bind_param("s",$BookISBN);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        
         public function getOrderDetail($OrderID) {
            $stmt = $this->con->prepare("SELECT deliveryDate,deliverystartTime,deliveryendTime FROM Delivery WHERE OrderID=?");
            $stmt->bind_param("s",$OrderID);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
		
		


		
		
		private function isUserExist($EmailID){
			$stmt = $this->con->prepare("SELECT EmailID FROM `Customer` WHERE EmailID=? ;");
			$stmt->bind_param("s", $EmailID);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}
		
			public function isAccountValid($EmailID){
			$stmt = $this->con->prepare("SELECT * FROM `Customer` WHERE EmailID = ? AND BillNO=0");
			$stmt->bind_param("s", $EmailID);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->num_rows > 0) {
			return 0;
			}
			else{
			return 1;}
		}
		
		
			public function printReturnMessage($EmailID){
		    if($this->isAccountValid($EmailID) == 0){
					$stmt = $this->con->prepare("SELECT Books.Title,Orders.OrderID,Books.BookISBN FROM Books INNER JOIN Orders ON Books.BookISBN=Orders.BookISBN WHERE EmailID=? AND OrderID IN (SELECT OrderID FROM Delivery WHERE status='1')");
			$stmt->bind_param("s", $EmailID);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->num_rows > 0) {
			return 0;
			}
			else{
			return 1;}
			}else{
			    return 2;}
			}
		
			public function enterOrderDetails($BookISBN,$EmailID,$startTime,$endTime){
 if($this->isAccountValid($EmailID)==0){

		return 3;
	}
	else    {   
			       if($this->isOrderRepeated($EmailID,$BookISBN)){
				return 0;
				}
	    else{
			  
			    
		    	    $stmt = $this->con->prepare("UPDATE Books SET NoOfCopies=NoOfCopies-1 WHERE BookISBN=?;");
                     $stmt->bind_param("s", $BookISBN);


			
			
		        	$stmt1 = $this->con->prepare("INSERT INTO `Orders` (`OrderID`, `orderDate`, `EmailID`, `BookISBN`) VALUES (NULL, CURRENT_TIMESTAMP, ?, ?);");
                    $stmt1->bind_param("ss", $EmailID, $BookISBN);




		        	$stmt2 = $this->con->prepare("INSERT INTO `Delivery` (`status`,
                     `deliverystartTime`, `deliveryendTime`, `OrderID`,`deliveryDate`) VALUES ('0', ?, ?, NULL,DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 2 DAY));");
                     $stmt2->bind_param("ss", $startTime, $endTime);




     
			    	if( $stmt->execute() &&  $stmt1->execute() &&   $stmt2->execute() ){
					return 1;
			    	}else{
					return 2;
			    	}  
				
			}  
			
		}
			
    }
			
			
			
			public function updateDeliveryStatus($OrderID)  {
			    	$stmt1 = $this->con->prepare("UPDATE Delivery SET status=1 WHERE OrderID=?");
                $stmt1->bind_param("s", $OrderID);
                if($stmt1->execute())   {return 0;}
                else {return 1;}
			    
			}
			
			public function enterReturnDetails($BookISBN,$EmailID,$startTime,$endTime,$OrderID){
			$stmt = $this->con->prepare("INSERT INTO `ReturnBook` (`EmailID`, `BookISBN`, `returnDate`, `startTime`, `endTime`) VALUES (?, ?,DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 2 DAY) , ?, ?);");
$stmt->bind_param("ssss", $EmailID,$BookISBN,$startTime,$endTime);


			
			
			$stmt1 = $this->con->prepare("UPDATE Books SET NoOfCopies=NoOfCopies+1 WHERE BookISBN=?;");
$stmt1->bind_param("s", $BookISBN);





			$stmt2 = $this->con->prepare("DELETE FROM Delivery WHERE OrderID=?");
       $stmt2->bind_param("s", $OrderID);




			 echo $this->sql;       
				if( $stmt->execute() &&  $stmt1->execute() &&   $stmt2->execute()
				 ){
					return 1;
				}else{
					return 2;
				}
			
			}
			
				private function isOrderRepeated($EmailID,$BookISBN){
			$stmt = $this->con->prepare("SELECT * FROM Delivery WHERE OrderID IN (SELECT OrderID FROM Orders WHERE BookISBN=? AND EmailID=?)");
			$stmt->bind_param("ss",$BookISBN,$EmailID);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}
			
		
	


	}
