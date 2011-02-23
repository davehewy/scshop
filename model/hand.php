<?php

	// This class literally handles the giving of the items
	// to the correct playerid and email passed to it via the call.

	class Hand extends Model{
	
	var $account = array();
	var $action;
	var $product = array();
	var $via;
	
	private $player_email;
	private $item_number;
	private $item_name;
	private $txn_id;
	private $amt_paid;
	private $quantity;
	
		function __construct(){
			parent::Model();
		}
		
		function in_it($via,$player_email,$item_number,$item_name,$txn_id,$amt_paid,$quantity=false){
			$this->via = $via;
			$this->player_email  = $player_email;
			$this->item_number = $item_number;
			$this->item_name = $item_name;
			$this->txn_id = $txn_id;
			$this->amt_paid = $amt_paid;
			$this->quantity = $quantity;
		}
		
		function update_player($email,$sql){
			$row_id = $this->db->query("select id from user_info where email='{$email}' order by id desc limit 1")->row();
			if($row_id):
			$this->db->query("update user_info set $sql where id='{$row_id['id']}'");
			endif;
		}
		
		
		# Utilities that are going to be hooked into accross all the systems for processing payments.
		function fetchName($itemNumber,$extra=false,$additional=false){
			
			// Determine what the real item number is.
			$item = $this->db->query("select name from products where id='{$itemNumber}'")->as_object();
			
			if($itemNumber!=5){
				$retName = $item->name;
					
			}else{
				$retName = ($extra*10).' '.$item->name;
			}
					
			return $retName;						

		}
		
		function fetchReference(){
			
			// Determine what the real item number is.
			$item = $this->db->query("select reference from products where id='{$itemNumber}'")->as_object();
			
			$reference = $item->reference;
			
			// If extra is present add more to the reference.
			
			if($extra){
				$reference.='-'.$extra;
			}			
			
			return $reference;
			
		}
		
		function fetchPlayer(){
			// Fetch the newest account by the paying email.
			$this->account = $this->db->query("select id,username,email,status from user_info where email='{$this->player_email}' order by id desc limit 1")->row();
		}
		
		function fetchAction($item_number){
			$action = $this->db->query("select name from products where itemnum='{$item_number}'")->row();
			$the_action = preg_split('/ /', $action['name']); 
			$this->action = $the_action[0];
			$this->product = $action;
			return $the_action[0];
		}
		
		// =========== 
		// ! Function which actually handles the handing of the goods to the players.   
		// =========== 
		
		function handGoods($action){
			$the_var = 'add'.$action;
			$this->$the_var();
			$this->fetchPlayer();
			
			// Only send a message to the player if there is a record of the player is alive.
			
			if($this->account['status']=='Alive'){
				
				$msg = sprintf(gettext("Your payment of &pound;%s via %s for %s has been successfully processed! The %s have been automatically added to your account. 
				
				If you haven't already, check out the [url=/world/creditshop.php]credit store[/url] credit store to see what items you can purchase with them."),number_format($this->amt_paid,2),$this->via,$this->item_name,$this->product['name']);
			
				$this->msgplayer($this->account['id'], $msg);
			}
			
			// Always send the account holder an email transaction to confirm it.
			
			$send_to_name = (is_empty($this->account['username'])) ? 'Player' : $this->account['username'];
			
			$this->load->library('mail');
	        $this->mail
	            ->setTo($this->player_email,$send_to_name);
	            ->setSubject($this->core->get_config_item('name','application')." - Forgotten Password")
	            ->setPlain()
	            ->setHtml()
	            ->send();
			
		}
		
		function msgPlayer($id,$msg){
		
			$mail = array(
				"sender"=>0,
				"receiver"=>$id,
				"message" => $msg,
				"Date" => time()
			);
			
			$data = $this->db->query("select id from msgchanges from playerid='$id'")->row();
			
			if($data){
				$this->db->query("update msgchanges set msgcount=msgcount+'1' where playerid='$id'");
			}else{
				$this->db->insert("msgchanges",array("playerid"=>$id,"msgcount"=>"1"));
			}
 			
			
			$this->db->insert("mail_in",$mail);
		
		}
		
		function notifyPlayerViaEmail(){
		
		
		}
		
		
		// # Process the goods
		
		// # Memberships
		
		function addMembership($id,$period){
			
			switch($id){
				case 1: $this->addElite($period); break;
				case 2: $this->addPremium($period); break;
			}
			
		}
		
		function addElite($period){
		
		}

		function addPremium($period){
		
		}		
		
		
		// # Credits
		
		function addCredits(){
			
			$this->update_player($this->player_email,"credits=credits+'$this->quantity'");
			
		}
		
		// # Merchandise
		
		function addMerch($id){
		
		
		}
		
		// # Personalise
		
		function personalise($id){
		
			switch($id){
				case 1: $this->charName();break;
				case 2: $this->charGender();break;
				case 3: $this->charEmail();break;
			}
		
		}
		
		function charName(){
		
		}
		
		function charGender(){
		
		}
		
		function charEmail(){
		
		}
	
	}