<?php

	class Callback extends Controller{
	
		function __construct(){
			parent::__construct();
		}	
	
		function paypal(){
				
			$this->load->model('paypalprocess');
			$this->paypalprocess->handle();
				
		}
	
	}