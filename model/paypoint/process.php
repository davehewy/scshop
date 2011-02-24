<?php

	 class Process extends Model{
	 	
	 	function __construct(){
	 		parent::__construct();
	 	}
	 	
	 	function handle(){
	 		
	 		// =========== 
	 		// ! There are a few steps to handling this payment from paypoint by Paypoint.
	 		// =========== 
	 		
	 		if(!is_empty($_POST)){
	 			
	 			# Before we go any further we must see that a few fields we are about to use exist and are set.
	 			
	 			if(isset($_POST['trans_id']) && isset($_POST['amount']) && isset($_POST['hash'])){
	 			
		 			$hashcode = md5("trans_id=".$_POST['trans_id']."&amount=".$_POST['amount']."&callback=http://shop.street-crime.com/callback/paypoint/&crunt0101");
		 			
		 			# Now were going to check if the posted hash code matches what we are expecting it to be.
		 			
		 			if($_POST['hash']==$hashcode){
		 			
		 				# It does so we will continue
		 				
		 				# First thing we will do is cleanse the post and move to a global.
		 				
						// Convert the post array to $_PAYPOINT
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
								$_PAYPOINT[$ipnkey] = $ipnval; // Assign data to new global array
								
							}// Destroy the original ipn post array, sniff...
						}
						
						// =========== 
						// ! Now before we do anything more we should check to see if this is a valid payment.   
						// =========== 
						
						if($this->validation->checkdata($_PAYPOINT['valid'],4)=='true'){
							
							# Wahoo its valid. We continue on.....

			 				// =========== 
			 				// ! Lets check if the transaction id sent to us from Paypoint has been presented already.   
			 				// =========== 
			 				
			 				$trans_id = trim($_PAYPOINT['trans_id']);
			 				
			 				$presented = $this->db->query("select id from ipn_paypoint where trans_id = '$trans_id'")->row();
			 				if(!$presented){
			 				
			 					// =========== 
			 					// ! Transaction has not yet been presented for process. So lets do it now.   
			 					// =========== 
			 					
			 					# First Step will be to record the transaction into ipn_paypoint table.
			 					
			 					// Due to the nature of generating unique id's to send to paypoint to be processed.
			 					// We pre generate rows in the ipn_paypoint_temp table.
			 					
			 					# Do they have a ipn_paypoint_temp row?
			 					
			 					$temp = $this->db->query("select * from ipn_paypoint_temp where player='{$_PAYPOINT['email']}' and trans_id='$trans_id'")->row();
			 					if($temp){
			 					
				 					$ins = array(
				 						"player" => $_PAYPOINT['email'],
				 						"trans_id" => $_PAYPOINT['trans_id'],
				 						"amount" => $_PAYPOINT['amount'],
				 						"auth_code" => $_PAYPOINT['auth_code'],
				 						"card_type" => $_PAYPOINT['card_type'],
				 						"time" => time()
				 					);
				 					
				 					$ipn_id = $this->db->insert("ipn_paypoint",$ins,true);
			 					
			 					}else{
			 					
			 						// If they didn't have a temp row and they just made a valid payment, so be it.
				 					$ins = array(
				 						"player" => $_PAYPOINT['email'],
				 						"trans_id" => $_PAYPOINT['trans_id'],
				 						"amount" => $_PAYPOINT['amount'],
				 						"auth_code" => $_PAYPOINT['auth_code'],
				 						"card_type" => $_PAYPOINT['card_type'],
				 						"time" => time()
				 					);
				 					
				 					$ipn_id = $this->db->insert("ipn_paypoint",$ins,true);			 						
			 					
			 					}
			 					
			 					# Either way we can now be certain we need to remove the row from the temp.
			 					
			 					$this->db->query("delete from ipn_paypoint_temp where id='{$temp['id']}'");
			 					
			 					
			 					// =========== 
			 					// ! Open up an instance   
			 					// =========== 
			 					
			 					$FURY =& get_instance();
			 					
			 					$FURY->load->model('hand');
			 					
			 					$playerid = $FURY->hand->fetchId($_PAYPOINT['email']);
			 							 					
			 					// =========== 
			 					// ! Payment has been recorded, shouldn't we now move this to a payments table for general recording?   
			 					// =========== 
			 					
			 					$sale_fields = array(
			 						"ipn_id" => $ipn_id,
			 						"trans_id" => $trans_id,
			 						"via" => 'Paypoint',
			 						"email" => $_PAYPOINT['email'],
			 						"playerid" => $playerid,
			 						"amount" => $_PAYPOINT['amount'],
			 						"item_num" => $_PAYPOINT['item_num'],
			 						"item_name" => $_PAYPOINT['item_name'],
			 						"time" => time()
			 					);
			 					
			 					$FURY->load->model('recordsale');
			 					$FURY->recordsale->record($sale_fields);
			 					
			 					// =========== 
			 					// ! Now we should probably handle the goods for the user.   
			 					// =========== 
			 					
			 					$quantity = (!$_PAYPOINT['quantity']) ? FALSE : $_PAYPOINT['quantity'];
			 								 					
			 					
			 					$FURY->in_it('Paypoint',$_PAYPOINT['email'],$_PAYPOINT['item_num'],$_PAYPOINT['item_name'],$trans_id,$_PAYPOINT['amount'],$quantity);
								
								$action = $FURY->hand->fetchAction($_PAYPOINT['item_num']);	
								
								$FURY->hand->handGoods($action);		 					
			 					
			 				}
		 				
		 				}else{
		 				
		 					// =========== 
		 					// ! The request was invalid and will not be processed.   
		 					// =========== 
		 					
		 					// We can try and use the code to work out a reason why it failed.
		 					$fail=1;
		 					
		 				
		 				}

		 			}else{
		 			
		 				// Failed due to hash mix being wrong.
		 				$fail = 1;
		 			}
	 			
	 			}else{$fail=1;}
	 		
	 		}else{$fail = 1;}
	 		
	 		return array("outcome"=>$fail,"pp_vars"=>array("tx_id"=>$trans_id,"amt"=>$_PAYPOINT['amount'],"item_name"=>$_PAYPOINT['item_name']));
	 		
	 	}
	 	
	 	function _bank_codes($code){
				if($code=='A'){
				$trans = "Transaction authorised by bank. auth_code available as bank reference.";
				}elseif($code=='N'){ 
				$trans = "Transaction not authorised. Failure message text available to merchant.";
				}elseif($code=='C'){
				$trans = "Communication problem. Trying again later it may well work.";
				}elseif($code=='P:A'){
				$trans = "Pre-bank checks. Amount not supplied or invalid.";
				}elseif($code=='P:X'){
				$trans = "Pre-bank checks. Not all mandatory parameters supplied";
				}elseif($code=='P:P'){
				$trans = "Pre-bank checks. Same payment presented twice.";
				}elseif($code=='P:S'){
				$trans = "Pre-bank checks. Start date invalid.";
				}elseif($code=='P:E'){
				$trans = "Pre-bank checks. Expiry date invalid.";
				}elseif($code=='P:I'){
				$trans = "Pre-bank checks. Issue number invalid.";
				}elseif($code=='P:C'){
				$trans = "Pre-bank checks. Card number fails LUHN check.";
				}elseif($code=='P:T'){
				$trans= "Pre-bank checks. Card type invalid - i.e. does not match card number prefix.";
				}elseif($code=='P:N'){
				$trans = "Pre-bank checks. Customer name not supplied.";
				}elseif($code=='P:M'){
				$trans = "Pre-bank checks. Merchant does not exist or not registered yet.";
				}elseif($code=='P:B'){
				$trans = "Pre-bank checks. Merchant account for card type does not exist.";
				}elseif($code=='P:D'){
				$trans ="Pre-bank checks. Merchant account for this currency does not exist.";
				}elseif($code=='P:V'){
				$trans ="Pre-bank checks. CV2 security code mandatory and not supplied / invalid.";
				}elseif($code=='P:R'){
				$trans = "Pre-bank checks. Transaction timed out awaiting a virtual circuit. Merchant may not have enough virtual 
				circuits for the volume of business. ";
				}elseif($code=='P:#'){
				$trans =" Pre-bank checks. No MD5 hash / token key set up against account ";
				}

			return $trans;
	 	
	 	}
	 	
	 }