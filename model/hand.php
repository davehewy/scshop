<?php

	// This class literally handles the giving of the items
	// to the correct playerid and email passed to it via the call.

	class Hand extends Model{
		
	private $player_email;
	private $item_number;
	
		function __construct(){
			parent::Model();
		}
		
		function in_it($player_email,$item,$txn_id){
			$this->player_email  = $player_email;
			$this->item_number = $item;
			$this->txn_id = $txn_id;
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
		
		function fetchPlayer($email){
			// Fetch the newest account by the paying email.
			$account = $this->realdb->query("select id,username,email from user_info where email='{$email}' order by id desc limit 1")->row();
			return $account->id;
		}
		
		
		function msgPlayer(){
		
		
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
		
		function addCredits($amt){
			
			
			
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