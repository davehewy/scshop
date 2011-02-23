<?php

	class Paypalprocess extends Model{
	
		function __construct(){
			parent::Model();
		}
		
		
		function writeToPaypalLog($string){
    		$myFile = $_SERVER['DOCUMENT_ROOT'].'/application/logs/payments/paypal.txt';
			$fh = fopen($myFile, 'a');
			fwrite($fh, $string);
			fclose($fh);		
		}
    			
		
		function handle(){
				
			$postipn = '';
			
			$log = serialize($_POST);
			
			$this->writeToPaypalLog('\n\nReporting from the first stage: '.$log.'\n\n');
	
					
			if (!@$_POST['txn_type']){
				@header("Status: 404 Not Found"); exit; 
			}else{
				@header("Status: 200 OK");  // Prevents ipn reposts on some servers
			}
			
			
			// Convert the post array to $PAYPAL
			foreach ($_POST as $ipnkey => $ipnval){
			// Fix issue with magic quotes
			if (get_magic_quotes_gpc()){
				$ipnval = stripslashes ($ipnval); 
			}
			
			if (!preg_match("/^[_0-9a-z-]{1,30}$/",$ipnkey) || !strcasecmp ($ipnkey, 'cmd')){ 
				// ^ Antidote to potential variable injection and poisoning
				unset ($ipnkey); unset ($ipnval); 
			} // Eliminate the above
			
			if (@$ipnkey != '') { // Remove empty keys (not values)
				$_PAYPAL[$ipnkey] = $ipnval; // Assign data to new global array
				
			}// Destroy the original ipn post array, sniff...
			
			
			$postipn.= '&'.@$ipnkey.'='.urlencode(@$ipnval); 
			
			}
			
			unset ($_POST); 
			
			
			//$this->writeToPaypalLog('\n\nReporting from the second stage: '.var_export($_PAYPAL).'\n\n');				
			
			$FURY =& get_instance();
			$FURY->load->model('paypal/verifyipn');		
			$FURY->verifyipn->setString($postipn);
			$FURY->verifyipn->verifyWithPaypal($_PAYPAL);
			
			
		} 

	}