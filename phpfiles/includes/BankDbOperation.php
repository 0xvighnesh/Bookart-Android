<?php

	class BankDbOperation{

		private $con;

		function __construct(){

			require_once dirname(__FILE__).'/BankDB_Connect.php';

			$db = new BankDB_Connect();

			$this->con = $db->connect();



		}

		/*CRUD -> C -> CREATE */

		public function completePayment($CardNo,$Month,$Year,$Cvv){
		    $var=$this->isvalidCard($CardNo,$Month,$Year,$Cvv);
		   
		    $var2=0;
		    if($var == $var2){
			$stmt = $this->con->prepare("SELECT account_number FROM Customer_debitcard WHERE debit_card_number=?;");
			$stmt->bind_param("s", $CardNo);	//Pull account number
			
			
			$stmt->execute();
			$result =  $stmt->get_result(); //storing sql_result object
		   $rows = mysqli_fetch_array($result); //converting to array
			$accountnumber=$rows[0];    //selecting first object of array
		
			$stmt = $this->con->prepare("SELECT balance FROM `Account` WHERE account_number =?;");
	
				$stmt->bind_param("s", $accountnumber);	//Pull account balance
				$stmt->execute();
			$result =  $stmt->get_result(); //storing sql_result object
			$rows = mysqli_fetch_array($result);    //converting to array
			$balance= $rows[0];  //selecting first object of array
		    
					if($balance>1999)   //checking balance limit
					{
					$temp=$balance;
					$temp=$temp-999;    //reducing customer balance
					$stmt = $this->con->prepare("UPDATE `Account` SET `balance` = ? WHERE `Account`.`account_number` 						= ?; ");
					$stmt->bind_param("ss", $temp,$accountnumber);
					$stmt1 = $this->con->prepare("UPDATE `Account` SET `balance` = (balance+999) WHERE `Account`.`account_number` = '0-1-3333-3333-3333';");  
					//adding balance into company account
				
					if($stmt->execute() && $stmt1->execute()){
					return 1;   //successful
					}else{
					return 2;   //unsuccessful
					}
					}	else {return 3;}
	}
	
	    else{

				return 0;   //invalid card
	    	}

	}
		private function isvalidCard($CardNo,$Month,$Year,$Cvv){
	
			//Check validity of card
				$stmt = $this->con->prepare("SELECT debit_card_number FROM `DebitCard` WHERE debit_card_number=? AND cvv=?;");
			$stmt->bind_param("ss", $CardNo,$Cvv);
			
			if($stmt->execute()){
			
			return 0;}
			
		
			
		}


	}
