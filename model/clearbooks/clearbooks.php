<?php

	class Clearbooks extends Model{
	
	const API_KEY = 'bytewireltd6147b5b2e1547ebb6e295';
	const CLEARBOOKS_URL = 'https://secure.clearbooks.co.uk/';
	private $_client;
	private $_lastinvoice;
 

		function __construct(){
			parent::Model();
		}
		
		function initialise(){	
			
			$this->_client = new SoapClient(self::CLEARBOOKS_URL.'api/wsdl/');
			
			$this->_client->__setSoapHeaders(array(
				new SoapHeader(self::CLEARBOOKS_URL . 'api/soap/',
					'authenticate', array('apiKey' => self::API_KEY)),
			));			
			
			
		}
		
		# Make all actions chainable if so wished.
		
		# Create an invoice.
		
		function createInvoice($params){
					
			$result = $this->_dispatch('createInvoice',$params);
			$this->_lastinvoice = $result->invoice_id;
			return $result;		
		
		}
		
		# Create a payment @chainable
		
		function createPayment($params){
		
			"dateCreated" => date("Y-m-d",time()),
			"dateDue" => date("Y-m-d",time()),
			"project" => 1,
			"type" => "sales",

			
			return $result = $this->_dispatch('createPayment',$params);
			
		}
		
		
		# Make the request to the clearbooks server with SOAP
		private function _dispatch($action,$params){
			
			return $this->_client->createInvoice($params);

		}
	
	
	}
	
