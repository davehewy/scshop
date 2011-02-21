    <?php  
      
    class VerifyIPN extends Model{
    
    private $req;
    
    	function __construct(){
    		parent::Model();
    	}
    	
    	// @chainable
    	
    	function setString($string){
    		// read the post from PayPal system and add 'cmd'  
    		$this->req = 'cmd=_notify-validate';  
			return $this;   
    	}
    	
  		// As part of this we will require a double checking
		// function to make sure they dont double receive goods.
		
		function checkDouble($transid){
			
			$q = $this->db_sc->query("select ipnid from ipn where trans='{$transid}'");
			if ($q->num_rows() > 0){
				return false;
			}else{
				return true;
			}
					
		}
    	
    	// Post back to PayPal system to validate  
    	
    	function verifyWithPaypal(){
		    
		    $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";  
		    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";  
		    $header .= "Content-Length: " . strlen($this->req) . "\r\n\r\n";  
		      
		    $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);  
		    
		    if (!$fp) {  
		    // HTTP ERROR  
		    } else {  
			    fputs ($fp, $header . $req);  
			    while (!feof($fp)) {  
				    $res = fgets ($fp, 1024);  
				    if (strcmp ($res, "VERIFIED") == 0) {  
				      
				    	// PAYMENT VALIDATED & VERIFIED! 
				    	if($_POST['payment_status']=="Completed"){
				    		
				    		$txn_id = $_PAYPAL['txn_id'];
							$player_email = $_PAYPAL['option_name1'];
							$item_number = $_PAYPAL['item_number'];
							
							// Now we need our database make a connection.
							$this->makeConnection();
							
							// Check the IPN is not in our database already.
							if($this->checkDouble($txn_id)){
							
								// Record the IPN in our database.
								
								$ins = array(
									"trans" => $_PAYPAL['txn_id'],
									"item_number" => $_PAYPAL['item_number'],
									"time" => time(),
									"verified" => $_PAYPAL['payment_status'],
									"total" => $_PAYPAL['payment_gross'],
									"payeremail" => $_PAYPAL['payer_email'],
									"table" => $_PAYPAL['first_name'],
									"secondname" => $_PAYPAL['second_name'],
									"street" => $_PAYPAL['address_street'],
									"city" => $_PAYPAL['address_city'],
									"state" => $_PAYPAL['address_state'],
									"zip" => $_PAYPAL['address_zip'],
									"country" => $_PAYPAL['address_country']
								);
								
								// Make the insert.
								
								$this->db->query->insert("ipn",$ins);
							
								// Hand the user the goods and email / invoice.
								$this->load->model('handgoods');
								$this->handgoods->in_it($player_email,$item_number,$txn_id);
								
								// Load the clearbooks model and handle some updates.
								
								$this->load->model('clearbooks/clearbooks');
								$this->clearbooks->initialise();
								
								// Create an invoice
								
								// Fetch some details before making the call 
								$item_name = $_PAYPAL['item_name'];
								$playerid = $this->handgoods->fetchPlayer($player_email);
								$reference = $this->handgoods->fetchReference($item_number,$_PAYPAL['option_name2']);
								
								$invoice = array(		
									"description" => "Paypal Payment - street-crime.com",
									"reference" => $reference,
									"items" => array(
										array(
											"description" => $item_name,
											"unitPrice" => $_PAYPAL['payment_gross'],
											"quantity" => 1,
											"type" => 1001001,
											"vat" => 0.00,
										),
									),
									"entityId" => $playerid,	
								);
								
								$ret = $this->clearbooks->createInvoice($invoice);
								
								// Pay the invoice to the paypal bank acc.
								
								if($ret->invoice_id){
																		
									$pay = array(
										'type' => 'purchases',
										'project' => 1,
										'accountingDate' => date("Y-m-d",time()),
										'description' => 'Paypal Payment - street-crime.com',
										'amount' => $_PAYPAL['payment_gross'],
										'entityId' => $playerid,
										'paymentMethod' => 21,
										'bankAccount' => "pp",
										'invoices' => array(
											array(
												'id' => $ret->invoice_id,
											),
										),
									);
								
 									$this->clearbooks->createPayment($pay);
								
								}
								
								
							
							}
				    		
				    	}else{
				    	// PAYMENT DELAYED | SHOOT MAIL TO PLAYER AND EMAIL TO LET THEM KNOW WHATS GOING ON
				    		
				    	}
				      
				    }  
				      
				    else if (strcmp ($res, "INVALID") == 0) {  
				      
				    // PAYMENT INVALID & INVESTIGATE MANUALY!  
				      
				    }  
			    }  
			    fclose ($fp);  
		    }  		    
		    
    	}
    
    } 
      

    ?>  