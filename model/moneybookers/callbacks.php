<?php

	class Callbacks extends Controller{
	
	// Define some constants.
	
	private $merchidMB = "19698083";
	
	
		function Callbacks(){
			parent::Controller();
		}
		
		function moneybookers(){
			
			// For security we should always check the authenticity of any request from moneybookers.
			// Look for some paramaters first.
			$this->merchantid = $this->input->post('merchant_id');
			$this->transid = $this->input->post('transaction_id');
			
			if($this->merchantid){	
				
				if($this->checkAuthenticity()){
				
					// Now check it against the transaction we have recorded.
					
				
				}
				
			}
			
		}
		
		private function checkAuthenticity(){
			
			// check ip range is valid.
			// Add in when we can.
			
			if($this->merchantid == $this->merchidMB){
			
				// Check if the transaction is uncollected.
				$this->db_sc = $this->realdb->connecttosc();
				
				if($trans = $this->db_sc->get_where('moneybookers',array("transid"=>$this->transid))->row()){
					
					// We know if it failed to get in here its already been collected.
					
				
				}
		
			}
			
			return false;
		
		}
		
	}