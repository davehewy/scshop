<?php

	class Home extends Controller{
		
		function __construct(){
			parent::__construct();
		}
		
		function index(){
		
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
			
			$page = array("header"=>$headerinfo,"product"=>$product_info,"cat"=>$ret_cat,"pricing"=>$pricing);
						
			$this->load->view('header');
			
			$this->load->view('credits',$page);
			$this->load->view('cards');
			$this->load->view("otherways");
			$this->load->view('footer');
			
		}

		function single_pricing($p){
			if($p->discount>0){
				$price = number_format(($p->price/100)*(100-$p->discount),2);
			}else{
				$price = $p->price;
			}
			return $price;
		}
		
		function monthy_pricing($p){
			if($p->discount>0){
				$price = number_format(((($p->price/100)*(100-$this->monthly_recurring))/100)*(100-$p->discount),2);
			}else{
				$price = number_format((($p->price/100)*(100-$this->monthly_recurring)),2);
			}
			return $price;
		}
		 

		function quarterly($p){
			if($p->discount>0){
				$price = number_format((((($p->price*3)/100)*(100-$this->three_month))/100)*(100-$p->discount),2);
			}else{
				$price = number_format(((($p->price*3)/100)*(100-$this->three_month)),2);
			}
			return $price;
		}
		
		function yearly($p){
			if($p->discount>0){
				$price = number_format((((($p->price*12)/100)*(100-$this->yearly))/100)*(100-$p->discount),2);
			}else{
				$price = number_format(((($p->price*12)/100)*(100-$this->yearly)),2);
			}
			return $price;
		}
		
		
	}