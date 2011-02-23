<?php

	class Complete extends Controller{
	
	
		function __construct(){
			parent::__construct();
		}
		
		function fetchitem($id){
			return $this->db->query("select * from products where itemnum='$id'")->row();
		}
		
		function paypal(){	
		
			// =========== 
			// ! Fetch some variables.   
			// =========== 
			
			if(!isset($_GET['tx'])){
			
				header("Location: /home");
			
			}else{
			
			$payment_vars = array(
			"tx" => (!isset($_GET['tx'])) ? FALSE : $_GET['tx'],
			"st" => (!isset($_GET['st'])) ? FALSE : $_GET['st'],
			"amt" => (!isset($_GET['amt'])) ? FALSE : $_GET['amt'],
			"cc" => (!isset($_GET['cc'])) ? FALSE : $_GET['cc'],
			"item_number" => (!isset($_GET['item_number'])) ? FALSE : $_GET['item_number']
			);
			
			if($item = $this->fetchitem($payment_vars['item_number'])){
				$payment_vars['item_name'] = $item['name'];
			}

			$this->load->model('shopcore');
			$headerinfo = $this->shopcore->headerinfo('Payment complete');
			
			$page = array("header"=>$headerinfo,"trans"=>$payment_vars);		
		
			$this->load->view('header',$page);
			$this->load->view('complete',$page);
			$this->load->view('footer');
			
			}
			
		}
	
	
	}