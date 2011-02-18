<?php

	class Shopcore extends Model{
	
		function __construct(){
			parent::__construct();
		}
		
		function headerinfo(){
		
			$header_array = array();
			
			$header_array['asset'] = $this->core->get_config_item('assets_url');
			
			return $header_array;
		
		}
	
	}