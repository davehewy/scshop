<?php

	Class Payswitch extends Model{
	
	const SMS_APPCODE = 58933;
	const SMS_CURRENCY = 'USD';
	
	var $payvars = array();
	var $pay_email = "payments@bytewire.co.uk";
	var $moneybookers = array("payment_methods"=>"VSA","pay_to_email"=>"dave@bytewire.co.uk","recipient_description"=>"STREET-CRIME.COM","transaction_id"=>"hygygyg919191","return_url"=>"http://street-crime.com","return_url_text"=>"Go back to Street Crime","return_url_target"=>"_parent","cancel_url"=>"http://street-crime.com","cancel_url_target"=>"_parent","status_url"=>"http://street-crime.com/success","confirmation_note"=>"Your good will be credited to your account as soon as the order is processed (this is usually instant) however please allow up to an hour before contacting an admin about not receiving an item.","currency"=>"USD","detail1_description"=>"Game:","detail1_text"=>"Street Crime");
	var $address_default = array("address"=>"Online","address2"=>"Online","phone_number"=>"021343","postal_code"=>"NOCODE","city"=>"Online","state"=>"Online");
	
	
	// =========== 
	// ! Paypoint settings   
	// =========== 
	
	var $ppoint_merchantid = 'bytewi01';
	var $ppoint_callback = 'http://shop.street-crime.com/callback/paypoint';
	var $ppoint_backcallback = 'http://shop.street-crime.com';
	var $ppoint_transid;
	
	
		function Payswitch(){
			parent::Model();
		}
		
		function model_load_model($model_name){
			$FURY =& get_instance();
			$FURY->load->model($model_name);
			return $FURY->$model_name;
		}
		
		
		
		function myUrlEncode($string) {
    		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    		$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    		return str_replace($entities, $replacements, urlencode($string));
		}
		
		function createUniqueId($playerid){
			//there doesnt appear to be a row.
			//hash up a unique id num
			$uniqueId = str_replace(
			array('+','/','='),
			array('-','_',''),
			base64_encode(file_get_contents('/dev/urandom', null, null, -1, 16)));
			
			//so we now have there unique id number
			
			$uniqueId = 'scprem-'.$uniqueId;
			
			//make a row in the db
			
			$table['transid']=$uniqueId;
			$table['playerid']=$this->payvars['playerid'];
			$this->db->insert("secpaytrans",$table);	
			
			return $uniqueId;
		}
		
		
		function moneybookers(){
						
			$pass['pass_mb'] = $this->moneybookers;
			$pass['pass_mb']['pay_from_email'] = $this->payvars['email'];
			$pass['pass_mb']['amount'] = $this->payvars['cost'];
			$pass['pass_mb']['detail2_description'] = "Product:";
			$pass['pass_mb']['detail2_text'] = $this->payvars['prodname'];
			
			
			if($this->payvars['product']!=5 && $this->payvars['product']!=6){
				foreach($this->address_default as $k=>$v){
					$pass['pass_mb'][$k] = $v;
				}
			}else{
				if(isset($this->payvars['incl_address'])){
					foreach($this->payvars['incl_address'] as $k=>$v){
						$pass['pass_mb'][$k] = $v;
					}
				}
				$pass['pass_mb']['hide_login'] = 0;
			}
			
			if($this->payvars['product']==7){
				$pass['pass_mb']['detail3_description'] = "Amount:";
				if($this->payvars['amount']<21){
					$pass['pass_mb']['detail3_text'] = ($this->payvars['amount']*10);
				}else{
					$pass['pass_mb']['detail3_text'] = 500;
				}
			}elseif($this->payvars['cat']==1){
				
			$pass['pass_mb']['detail3_description'] = "Type:";
			$pass['pass_mb']['detail3_text'] = $this->payvars['thetype'];
				
				if($this->payvars['duration']==2){
					
					$pass['pass_mb']['amount'] = 0;
					$pass['pass_mb']['rec_amount'] = $this->payvars['cost'];
					$pass['pass_mb']['rec_start_date'] = date("d/m/Y",time());
					$pass['pass_mb']['rec_period'] = '31';
					$pass['pass_mb']['rec_cyle'] = 'month';
					$pass['pass_mb']['rec_grace_period'] = '5';
					$pass['pass_mb']['rec_status_url'] = 'http://street-crime.com';

				}
			}elseif($this->payvars['product']==5 || $this->payvars['product']==6){
			

				// Set the address options and autofill the customer.
				if(isset($this->payvars['incl_address'])){
					foreach($this->payvars['incl_address'] as $k=>$v){
						
						$pass['pass_mb'][$k] = $v;
						
					}
				}
				
			}
			
			
			$this->load->view('moneybookers',$pass);
			
		}
		
		function paypal(){
			
			// # Specify some prerequisites which will likely be the same for everything.
	
			
			if(!isset($this->payvars['additional_text'])){ 
				$this->payvars['additional_text'] = '';
			}
			
						
			# Load in the handgoods model
			$FURY =& get_instance();
			$FURY->load->model('hand');
			$item_name = $FURY->hand->fetchName($this->payvars['product'],$this->payvars['amount'],$this->payvars['additional_text']);
						
			$query_string = '';
			
			if(isset($this->payvars['include_address'])){

				$address_vars = array(
					"address_override" => 1,
					"address1" => $this->payvars['include_address']['address'][0],
					"address2" => $this->payvars['include_address']['address'][1],
					"city" => $this->payvars['include_address']['city'],
					"first_name" => $this->payvars['include_address']['firstname'],
					"last_name" => $this->payvars['include_address']['secondname'],
					"state" => $this->payvars['include_address']['state'],
					"country" => $this->payvars['include_address']['country'],
					"zip" => $this->payvars['include_address']['postal_code']
				);
			
			}
			
			
			# Set the recurring variables if present.
			
			if(isset($this->payvars['recurring'])){
			
				# Recurring billing go
				
				$pre = array(
					"cmd" => "_xclick-subscriptions",
					"business" => "payments@bytewire.co.uk",
					"item_name" => $item_name,
					"return" =>  urlencode("http://shop.street-crime.com/complete"),
					"cancel_return" =>  urlencode("http://shop.street-crime.com/"),
					"quantity" => "1",
					"a3" => number_format($this->payvars['cost'],2),
					"p3" => 1,
					"t3" => "M",
					"src" => 1,
					"sra" => 1,
					"no_note" => 1,
					"on0" => $this->payvars['email'],
					"os0" => "player_email",
					"on1" =>  $this->payvars['on1'],
					"os1" => $this->payvars['on1_name'],
					"currency_code" => htmlspecialchars("USD")
				);
				
			}else{
			
				# Single payment go
				
				$pre = array(
					"cmd" => "_xclick",
					"business" => "seller_1282048892_biz@bytewire.co.uk",
					"quantity" => "1",
					"currency_code" => htmlspecialchars("USD"),
					"no_shipping" => "0",
					"no_note" => "1",
					"bn" => "PP-BuyNowBF",
					"return" =>  urlencode("http://shop.street-crime.com"),
					"notify_url" => urlencode("https://shop.street-crime.com/callback/paypal"),
					"item_name" => $item_name,
					"item_number" => $this->payvars['product'],
					"amount" => number_format($this->payvars['cost'],2),
					"on0" => $this->payvars['email'],
					"option_name0" => "player_email",
				);
				
				# Check for further optional extras.
				
				if(isset($this->payvars['on1'])){
					$extendables = array(
						"on1" =>  $this->payvars['on1'],
						"option_name1" => $this->payvars['on1_name']
					);
					
					$pre = array_merge($pre,$extendables);
					
				}
	
				
				if(isset($address_vars)){
					$pre = array_merge($pre,$address_vars);
				}
				

			}
			
		
			
/*
			array_walk($pre , create_function('&$v,$k', '$v = $k."=".$v ;'));
			$url = "https://www.paypal.com/cgi-bin/webscr?".htmlentities(urlencode(implode("&",$pre)), ENT_QUOTES);
*/
			
			$loop = 1;
			foreach($pre as $k=>$v){
				if($loop!=count($pre)){
					$query_string.=$k.'='.$v.'&';
				}else{
					$query_string.=$k.'='.$v;
				}
				$loop++;
			}
			
/*
			echo '<pre>';
			print_r($pre);
			echo '</pre>';
*/
			
			header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?".$query_string);
			
		}
		
		function paypoint(){
			
			// Does this user have a payment waiting to go somewhere?

			$q = $this->db->query("select transid from secpaytrans where playerid='{$this->payvars['playerid']}' and used='0'")->row();
			if($q){
				$transid = $q['transid'];
			}else{
				$transid = $this->createUniqueId();
			}
			
			$pass = 'crunt0101';
			
			$amount = round($this->payvars['cost'],2);
			$digest = $transid.$amount.$pass;
			$digest = MD5($digest);


			// Make an array to encode to send.
			$encode_array = array(
				"merchant"=>$this->ppoint_merchantid,
				"trans_id"=>$transid,
				"amount"=>round($this->payvars['cost'],2),
				"callback"=>$this->ppoint_callback,
				"digest"=>$digest,"backcallback"=>$this->ppoint_backcallback,
				"show_back"=>"back",
				"order"=>$this->payvars['product'],
				"options"=>"cb_post=true,md_flds=trans_id:amount:callback,merchant_logo=<img src=http://www.street-crime.com/images/payinglogo.jpg class=floatleft alt=Street Crime height=72>"
			);
			
			$query_string = '';
			$loop = 1;
			
			foreach($encode_array as $k=>$v):
				if($loop!=count($encode_array)){
					$query_string.=$k.'='.$v.'&';
				}else{
					$query_string.=$k.'='.$v;
				}
				$loop++;				
			endforeach;
			
			header("Location: https://www.secpay.com/java-bin/ValCard?".$query_string);
		
		}
		
		function sms(){
									
			$array = array( "query_string" => array(
				"appcode" => self::SMS_APPCODE,
				"currency" =>  self::SMS_CURRENCY,
				"price" => $this->payvars['cost'],
				"product" => $this->payvars['product'],
				"prodcode" => $this->payvars['product'],
				"gamepaytype" => "sms",
				"style" => 1
				)
			);
			
			$i = 0;
			$qString = '';
			
			foreach($array['query_string'] as $key=>$val):
				if($i!=0){
					$qString.="&".$key.'='.$val;
				}else{
					$qString.=$key.'='.$val;
				}
			$i++;
			endforeach;
			
			$data['qString'] = $qString;
			
			$this->shopcore = $this->model_load_model('shopcore');
			$headerinfo = $this->shopcore->headerinfo('Street Crime Shop - Pay Via SMS');
			
			$page = array("header"=>$headerinfo);
			
			$this->load->view('header',$page);
			$this->load->view("pay/sms",$data);
			$this->load->view('footer');
			
		}
		
		function bankTransfer(){
		
			$this->shopcore = $this->model_load_model('shopcore');
			$headerinfo = $this->shopcore->headerinfo('Street Crime Shop - Pay Via SMS');
			
			$page = array("header"=>$headerinfo);
			
			$this->load->view('header',$page);		
		
			$this->load->view('bankTransfer');
			
			$this->load->view('footer');
		
		}
		
		
		function initiate($fields){
			
			if($fields['type']){
			
				// Make them a permantent part of this class.
				foreach($fields as $k=>$v){
					$this->payvars[$k] = $v;
				}
			
				switch($this->payvars['type']){
					
					case 1: $this->paypal();break;
					case ($this->payvars['type']>=2 && $this->payvars['type']<=5): $this->paypoint(); break;
					case 6: $this->moneybookers();break;
					case 7: $this->bankTransfer();break;
					case 8: $this->sms();break;
					
				}
			
			}
			
		}
		
	}