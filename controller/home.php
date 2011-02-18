<?php

	class Home extends Controller{
		
		function __construct(){
			parent::__construct();
		}
		
		function index(){
		
			$this->load->helper('form_helper');
			
			// initialise some data
			$data = array();
			
			$this->load->model('shopcore');
			$data['header'] = $this->shopcore->headerinfo();
			
			$this->load->view('header',$data);
			$this->load->view('footer');
			echo 'make me some god damn pie mum';
			
		}
		
	}