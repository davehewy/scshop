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
			$ret = $this->process->handle();
			
			$this->load->model('shopcore');
			$headerinfo = $this->shopcore->headerinfo('Street Crime Shop - Credits');		
			
			$page = array("header"=>$headerinfo,"ret_info"=>$ret);
			
			$this->load->view('header_alt',$page);
			$this->load->view('pay/paypoint',$page);
			$this->load->view('footer');

		}
	
	
	}