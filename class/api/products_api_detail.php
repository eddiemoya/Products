<?php

class Products_Api_Detail extends Products_Api_Base implements Products_Api_Type {
	
	
	public function __construct() {
		
		 parent::__construct();
		 
		 $this->_method('productdetail');
	}
	
	public function detail($id) {
		
		$this->_param('partNumber', $id);
	}
}