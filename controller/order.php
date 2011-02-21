<?php

	class Order extends Controller{
	
	var $payment_fields = array();
	var $errors = 0;
	var $error_msgs = array();
	
		function __construct(){
			parent::__construct();
		}
		
		function single_pricing($p){
			if($p->discount>0){
				$price = number_format(($p->price/100)*(100-$p->discount),2);
			}else{
				$price = $p->price;
			}
			return $price;
		}		
		
		function start($action){
		
			$fuckaduck = $action;
			
			$this->load->library('input');
			$this->load->library('validation');
									
			if($this->validation->checkdata($action,3)){
			
				$p = $this->db->query("select * from products where name='{$action}'")->as_object();
								
				if($p){
										
					if($this->input->post('account') && $this->input->post('selectamount') && $this->input->post('type')){
					
						if($this->validation->valid_email($this->input->post('account'))){
						
							$email = $this->input->post('account');
						
							// Check to see if the email exists and it hooks to an account.
														
							$user_info = $this->db->query("select id from user_info where email='{$email}'")->row();
							if($user_info){
						
							if($amount = $this->validation->checkdata($this->input->post('selectamount'),1)){	
						
							
							if($this->input->post('type')==8){
								if($amount<=2){
									$continue = 1;
								}
							}else{
								$continue =1;	
							}
							
							if(isset($continue)==1){
													
								switch($amount){
									default:
											$cost = (((($p->price*10)*$amount)/100)*(100-$amount));
											break;
									case 1:	
											$cost = ($p->price*10);
											break;
									case 21:
											$cost = 75;
											break;
								}
								
								
								// Factor in the discount
								if($p->discount>0){
									$cost = round(($cost/100)*(100-$p->discount),2);
								}
									
																																	
								$this->payment_fields = array(
								"email"=>$this->input->post('account'),
								"type"=>$this->input->post('type'),
								"on1"=>($amount*10),
								"on1_name"=> "amount",				
								"product"=>$p->itemnum,
								"amount"=>$amount,
								"cost"=>$cost,
								"prodname"=>$p->name,
								"cat"=>$p->category,
								"playerid"=>$user_info['id']
								);
								
								
								$this->load->model('payswitch');
				
								# Redirect to the send payment model				
				
								$this->payswitch->initiate($this->payment_fields);
								
							}else{ $this->validation->addError("You cannot buy this many credits through SMS, the limit for one transaction is 50. Please use another payment method to buy more at once.");}
															
							}else{
								$this->validation->addError("Amount was not valid");
							}
							
							}else{
								$this->validation->addError("The email you entered does not match a character in our database, please create a character before trying to pay for any goods.");
							}
						
						}else{
							$this->validation->addError("Email was not valid");
						}	
									
					}else{
						$this->validation->addError("Not all fields selected");
					}
					
					}else{
						$this->validation->addError("No product returned");
					}

					
					if($this->validation->isErrors()){
					
						
						$this->load->helper('form_helper');
						$this->load->helper('url_helper');
						$this->load->library('session');
						$this->load->library('input');
						
						// initialise some data
						
						$this->load->model('shopcore');
						$headerinfo = $this->shopcore->headerinfo('Street Crime Shop - Credits');
						
						// Get the product info
						$product_info = $this->db->query("select * from products where id='5'")->as_object();
						
						// Get the category info.
						$ret_cat = $this->db->query("select * from categories where id='{$product_info->category}'")->as_object();
									
						// Work out the pricing
						$pricing = array("single"=>$this->single_pricing($product_info));
						
						$page = array("header"=>$headerinfo,"product"=>$product_info,"cat"=>$ret_cat,"pricing"=>$pricing,"errors"=>$this->validation->displayErrors());
									
						$this->load->view('header',$page);
						$this->load->view('credits',$page);
						$this->load->view('cards');
						$this->load->view("otherways");
						$this->load->view('footer');

					
					}

				
			}
			
		}
		
	
	}