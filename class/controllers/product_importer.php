<?php

class Product_Importer {
	
	public $partnumbers;

	
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
					
				}
				
				//if valid response, insert product post, meta data, and taxonomy
			}
		}
		
	}
	
	protected function _insert_post() {
		
	}
	
	
}