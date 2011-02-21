<?php

	class Paypalprocess extends Model{
	
		function __construct(){
			parent::Model();
		}
		
		function handle(){
		
			
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
			if (!eregi("^[_0-9a-z-]{1,30}$",$ipnkey)|| !strcasecmp ($ipnkey, 'cmd')){ 
				// ^ Antidote to potential variable injection and poisoning
				unset ($ipnkey); unset ($ipnval); 
			} // Eliminate the above
			if (@$ipnkey != '') { // Remove empty keys (not values)
				@$_PAYPAL[$ipnkey] = $ipnval; // Assign data to new global array
				unset ($_POST); 
			}// Destroy the original ipn post array, sniff...
		
			
			$postipn = '&'.@$ipnkey.'='.urlencode(@$ipnval); }} 			
			
			$this->load->model('paypal/verifyIPN');
			$this->verifyipn->setString($postipn)->verifyWithPaypal();
			
			
		} 

	}