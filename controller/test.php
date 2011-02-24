<?php

	class Test extends Controller{
	
		function __construct(){
			parent::__construct();
			
			$this->load->library('session');
			
		}
		
		function index(){
			
	 		$this->load->view('email/example');
	 		
	 		echo $this->core->get_config_item('zend_remote_path');
				
		}
	
	}