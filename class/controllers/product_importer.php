<?php

class Product_Importer {
	
	public $partnumbers;
	
	public $errors = array();
	
	public $msg = array();
	
	public $num_products_imported = 0;
		
	
	public function __construct(array $partnumbers) {
		
		$this->partnumbers = $partnumbers;
	}
	
	public static function factory(array $partnumbers) {
		
		return new Product_Importer($partnumbers);
	}
	
	public function import() {
		
		if(count($partnumbers)) {
			
			foreach($partnumbers as $partnumber) {
				
				$product = Products_Api_Request::factory(array('api' => 'detail',
																'term' => $partnumber))
												->response();

				//Check that we got a valid response
				if($product->success){
					
					//Check if product already exists in WP, if not proceed
					//If it exists log in errors
					
					//Insert product post, meta data, and taxonomy
					
					$this->num_products_imported++;
					
				} else {
					
					$this->_set_error($partnumber . ' was not imported - not found.');
				}
				
				if(count($this->errors)) {
					
					$this->_set_msg('There were issues importing some of the products.');
					
				} else {
					
					$this->_set_msg('All products were imported successfully. ' . $this->num_products_imported . ' products imported');
				}
			}
			
		} else {
			
			$this->_set_message('Please select at least one product to import.');
		}
		
		return $this;
		
	}
	
	protected function _insert_post() {
		
	}
	
	protected function _set_msg($text) {
		
		$this->msg[] = $text;
	}
	
	protected function _set_error($error) {
		
		$this->errors[] = $error;
	}
	
}