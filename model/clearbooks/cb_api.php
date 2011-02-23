<?php



	class Cb_api extends Model{
	
	const API_KEY = 'bytewireltd6147b5b2e1547ebb6e295';
	const CLEARBOOKS_URL = 'https://secure.clearbooks.co.uk/';
	private $_client;
	private $_lastinvoice;
 

		function __construct(){
			parent::Model();
		}
		
		function write_log($string){		
    		$myFile = $_SERVER['DOCUMENT_ROOT'].'/application/logs/clearbooks/log.txt';
			$fh = fopen($myFile, 'a');
			fwrite($fh, $string);
			fclose($fh);		
		}		
		
		function initialise(){	
			
			$this->_client = new SoapClient(self::CLEARBOOKS_URL.'api/wsdl/');
			
			$this->_client->__setSoapHeaders(array(
				new SoapHeader(self::CLEARBOOKS_URL . 'api/soap/',
					'authenticate', array('apiKey' => self::API_KEY)),
			));			
			
			$this->write_log("Finished initialising the api\n");
			
		}
		
		# Make all actions chainable if so wished.
		
		# Create an invoice.
		
		function createInvoice($params){
		
			$params_2 = serialize($params);
			$this->write_log("attempting to create an invoice with this:".$params_2.'\n');
					
			$result = $this->_dispatch('createInvoice',$params);
			$this->_lastinvoice = $result->invoice_id;
			return $result;		
		
		}
		
		# Create a payment @chainable
		
		function createPayment($params){
			
			$params_2 = serialize($params);
			$this->write_log("attempting to create an invoice with this:".$params_2.'\n');
			return $result = $this->_dispatch('createPayment',$params);
			
		}
		
		
		# Make the request to the clearbooks server with SOAP
		private function _dispatch($action,$params){
			$this->write_log("dispatching method call: ".$action.'\n');
			return $this->_client->$action($params);

		}
	
	
	}
	
