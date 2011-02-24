<?php

	class Shopcore extends Model{
	
		function __construct(){
			parent::__construct();
		}
		
		function headerinfo($title=false){
		
			$header_array = array();
			$header_array['title'] = (!isset($title)) ? "Street Crime Shop" : $title;
			$header_array['asset'] = $this->core->get_config_item('assets_url');
			$header_array['site_url'] = $this->core->get_config_item('site_url');
			$header_array['base_url'] = $this->core->get_config_item('base_url');
			
			return $header_array;
		
		}
	
	}