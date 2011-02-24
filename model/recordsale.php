<?php

	class Recordsale extends Model{
	
		function __construct(){
			parent::__construct();
		}
		
		function record($array){
		
			$this->db->insert("sc_payments",$array);
		
		}
	
	}