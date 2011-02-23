<?php

	class Test extends Controller{
	
		function __construct(){
			parent::__construct();
			
			$this->load->library('session');
			
		}
		
		function index(){
			
	 		$this->load->view('email/example');
				
		}
	
	}