<?php

	class Callback extends Controller{
	
		function __construct(){
			parent::__construct();
			$this->load->library('input');
		}	
	
		function paypal(){
			
			$this->load->model('paypal/paypalprocess');
			$this->paypalprocess->handle();
				
		}
		
		function sms(){
			
			$this->load->model('daopay/process');
			$this->process->handle();
			
		}
		
		function moneybookers(){
			$this->load->model('moneybookers/process');
		}
		
		function paypoint(){
			$this->load->model('paypoint/process');
		}
	
	}