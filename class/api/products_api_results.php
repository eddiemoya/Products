<?php

class Products_Api_Results {
	
	protected $_raw_response;
	
	public $num_pages;
	
	public $num_products;
	
	public $page;
	
	
	public function __construct(Products_Api_Type $obj) {
		
		$this->_raw_response = $obj->response();
		
		$this->num_pages = $obj->num_pages();
		
		$this->num_products = $obj->num_products();
		
		$this->page = $obj->page();
	}
	
}