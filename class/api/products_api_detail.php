<?php

class Products_Api_Detail extends Products_Api_Base implements Products_Api_Type {
	
	
	public function __construct() {
		
		 parent::__construct();
		 
		 $this->_method('productdetails');
	}
	
	public function detail($id) {
		
		$this->_param('partNumber', $id);
		
		return $this;
	}
	
	public function response() {
		
		return $this->_response;
	}
	
	public function num_pages() {
		
		return 1;
	}
	
	public function num_products() {
		
		return 1;
	}
	
	public function page() {
		
		return 1;
	}
	
	public function load() {
		
		$this->_load();
		
		return $this;
	}
}
