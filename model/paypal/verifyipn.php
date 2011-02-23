    <?php  
    
 // PHP 4.1

// read the post from PayPal system and add 'cmd'
/*
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];

if (!$fp) {
// HTTP ERROR
} else {
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);
if (strcmp ($res, "VERIFIED") == 0) {
// check the payment_status is Completed
// check that txn_id has not been previously processed
// check that receiver_email is your Primary PayPal email
// check that payment_amount/payment_currency are correct
// process payment
}
else if (strcmp ($res, "INVALID") == 0) {
// log for manual investigation
}
}
fclose ($fp);
}
?>   
*/
    
      
    class Verifyipn extends Model{
    
    var $req;
    
    	function __construct(){
    		parent::Model();
    		$this->load->library('db');
    	}
    	
    	
    	// @chainable
    	
    	function setString($string){
    		// read the post from PayPal system and add 'cmd'  
    		$this->req = 'cmd=_notify-validate'.$string;  
			return $this;   
    	}
    	
  		// As part of this we will require a double checking
		// function to make sure they dont double receive goods.
		
		function checkDouble($transid){
			
			$q = $this->db->query("select id from ipn_paypal where txn_id='{$transid}'")->row();
			if ($q){
				return false;
			}else{
				return true;
			}
					
		}
		
		function writeToPaypalLog($string){
    		$myFile = $_SERVER['DOCUMENT_ROOT'].'/application/logs/payments/paypal.txt';
			$fh = fopen($myFile, 'a');
			fwrite($fh, $string);
			fclose($fh);		
		}
    	
    	// Post back to PayPal system to validate  
    	
    	function verifyWithPaypal($_PAYPAL){
    	
    		    			
    		// Write to the log file.
			$this->writeToPaypalLog('Starting to write to the paypal log with var '.$this->req.'\r\n');
			
			$log = serialize($_PAYPAL);
		    
		    $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";  
		    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";  
		    $header .= "Content-Length: " . strlen($this->req) . "\r\n\r\n";  
		      
		    $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);  
		    
		   			   	
			// refer to each ipn variable by reference (recommended)
			$receiver_email = $_PAYPAL['receiver_email'];
			$receiver_id = $_PAYPAL['receiver_id'];
			$business = $_PAYPAL['business'];
			$item_name = $_PAYPAL['item_name'];
			$item_number = $_PAYPAL['item_number'];
			$quantity = $_PAYPAL['quantity'];
			$invoice = $_PAYPAL['invoice'];
			$custom = $_PAYPAL['custom'];
			$option_name1 = $_PAYPAL['option_name1'];
			$option_selection1 = $_PAYPAL['option_selection1'];
			$option_name2 = $_PAYPAL['option_name2'];
			$option_selection2 = $_PAYPAL['option_selection2'];
			$num_cart_items = $_PAYPAL['num_cart_items'];
			$payment_status = $_PAYPAL['payment_status'];
			$pending_reason = $_PAYPAL['pending_reason'];
			$payment_date = strtotime($_PAYPAL['payment_date']);
			$settle_amount = $_PAYPAL['settle_amount'];
			$settle_currency = $_PAYPAL['settle_currency'];
			$exchange_rate = $_PAYPAL['exchange_rate'];
			$payment_gross = $_PAYPAL['payment_gross'];
			$payment_fee = $_PAYPAL['payment_fee'];
			$mc_gross = $_PAYPAL['mc_gross'];
			$mc_fee = $_PAYPAL['mc_fee'];
			$mc_currency = $_PAYPAL['mc_currency'];
			$tax = $_PAYPAL['tax'];
			$txn_id = $_PAYPAL['txn_id'];
			$txn_type = $_PAYPAL['txn_type'];
			$reason_code = $_PAYPAL['reason_code'];
			$for_auction = $_PAYPAL['for_auction'];
			$auction_buyer_id = $_PAYPAL['auction_buyer_id'];
			$auction_close_date = $_PAYPAL['auction_close_date'];
			$auction_multi_item = $_PAYPAL['auction_multi_item'];
			$memo = $_PAYPAL['memo'];
			$first_name = $_PAYPAL['first_name'];
			$last_name = $_PAYPAL['last_name'];
			$address_street = $_PAYPAL['address_street'];
			$address_city = $_PAYPAL['address_city'];
			$address_state = $_PAYPAL['address_state'];
			$address_zip = $_PAYPAL['address_zip'];
			$address_country = $_PAYPAL['address_country'];
			$address_status = $_PAYPAL['address_status'];
			$payer_email = $_PAYPAL['payer_email'];
			$payer_id = $_PAYPAL['payer_id'];
			$payer_business_name = $_PAYPAL['payer_business_name'];
			$payer_status = $_PAYPAL['payer_status'];
			$payment_type = $_PAYPAL['payment_type'];
			$notify_version = $_PAYPAL['notify_version'];
			$verify_sign = $_PAYPAL['verify_sign'];
			$amount = $_PAYPAL['amount'];
			// username and user id
			$user_email = $option_name1;
			$item_quants = $option_name2;
			
		    if (!$fp) {  
		    
		    // HTTP ERROR
		    $this->writeToPaypalLog(serialize($_PAYPAL).' I died with an http error\r\n');

		    } else {  
		    
			    fputs ($fp, $header . $this->req);
			      
			    while (!feof($fp)) {  
				    $res = fgets ($fp, 1024); 
				    
				    $this->writeToPaypalLog($res.'this is sposed to be the contents of this file\r');

				    if (strcmp ($res, "VERIFIED") == 0) {  
				    
					$this->writeToPaypalLog('VERIFIED | '.$payment_status.'\n');
				      
				    	// PAYMENT VALIDATED & VERIFIED! 
				    	if($payment_status=="Completed"){
				    		
							
							$this->writeToPaypalLog('\nCOMPLETED | '.$payment_status.'\n');

							
							// Now we need our database make a connection.
							//$this->makeConnection();
							
							// Check the IPN is not in our database already.
							if($this->checkDouble( $txn_id )){
							
								// Record the IPN in our database.
								
								$ins = array(
									"txn_id" => $txn_id,
									"txn_type" => $txn_type,
									"status" => $payment_status,							
									"time" => $payment_date,
									"gross" => ($payment_gross <=0 ) ? $mc_gross : $payment_gross,
									"fee" => $payment_fee,
									"net" => $mc_gross,
									"verified" =>'1',
									"item_num" => $item_number,
									"item_name" => $item_name,
									"item_quants" => $item_quants,
									"payer_email" => $user_email,
									"paypal_email" => $payer_email,
									"payer_id" => $payer_id,
									"first_name" => $first_name,
									"second_name" => $last_name,
									"street" => $address_street,
									"city" => $address_city,
									"state" => $address_state,
									"zip" => $address_zip,
									"country" => $address_country
								);
								
								// Determine the amount paid.
								$amount_paid = ($payment_gross <=0 ) ? $mc_gross : $payment_gross;
								
								// Make the insert.
								
								$this->db->insert("ipn_paypal",$ins);
								
				
								// =========== 
								// ! Handle goods, ingame message and invoice email   
								// =========== 
								$FURY =& get_instance();
								
								// Hand the user the goods and email / invoice.
								
								$FURY->load->model('hand');
								$FURY->hand->in_it('Paypal',$user_email,$item_number,$item_name,$txn_id,$amount_paid,$quantity);
								
								$FURY->hand->fetchAction($item_number);
								
								// Fetch some details before making the call 
								//$playerid = $FURY->hand->fetchPlayer($payer_email);
								//$reference = $FURY->hand->fetchReference($item_number,$option_name2);

								// hand the player the goods
								$FURY->hand->handGoods('Credits');
								
								// =========== 
								// ! End handling of goods, start clearbooks integration   
								// =========== 
								
/*
								#Register with clearbooks
								$FURY->load->model('clearbooks/cb_api');
								$FURY->cb_api->initialise();
								
								$invoice = array(		
									"description" => "Paypal Payment - street-crime.com",
									"reference" => $reference,
									"dateCreated" => date("Y-m-d",time()),
									"dateDue" => date("Y-m-d",(time()+3600)),
									"project" => 1,
									"type" => "sales",
									"items" => array(
										array(
											"description" => $item_name,
											"unitPrice" => $payment_gross,
											"quantity" => 1,
											"type" => 1001001,
											"vat" => 0.00,
										),
									),
									"entityId" => $user_mail,	
								);
								
								$ret = $FURY->cb_api->createInvoice($invoice);
								
								// Pay the invoice to the paypal bank acc.
								
								if($ret->invoice_id){
																		
									$pay = array(
										'type' => 'purchases',
										'project' => 1,
										'accountingDate' => date("Y-m-d",$payment_date),
										'description' => 'Paypal Payment - street-crime.com',
										'amount' => $payment_gross,
										'entityId' => $user_mail,
										'paymentMethod' => 21,
										'bankAccount' => "pp",
										'invoices' => array(
											array(
												'id' => $ret->invoice_id,
											),
										),
									);
								
 									$FURY->cb_api->createPayment($pay);
								
								}
								
*/								
								
								
								
							
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