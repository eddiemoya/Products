<?php

class Products_Api_Search extends Products_Api_Base implements Products_Api_Type {
	
	
	function __construct() {
		
		parent::__construct();
		
		$this->_method('productsearch');
		
	}
	
	public function keyword($text) {
		
		$this->_param('searchType', 'keyword')
			 ->_param('keyword', $text);
			 
		
		return $this;
	}
	
	public function vertical($vertical) {
		
		$this->_param('searchType', 'vertical')
				->_param('verticalName', $vertical);
			
		return $this;
	}
	
	/**
	 * category()
	 * 
	 * @param unknown_type $vertical
	 * @param unknown_type $category
	 * @param unknown_type $sub_category
	 */
	public function category(array $args) {
		
		$this->_param('verticalName', $args['vertical'])
          		->_param('categoryName', $args['category']);

        if (! isset($args['sub_category'])) {
        	
            $this->_param('searchType', 'category');
            
        } else {
        	
              $this->_param('searchType', 'subcategory')
                	->_param('subCategoryName', $args['sub_category']);
        }

        return $this;
	}
	
	public function load() {
		
		$this->_load();
		
		return $this;
	}

	public function response() {
		
		return $this->_response;
	}
	
	public function num_products() {
		
		return (isset($this->_response->mercadoresult->productcount)) ? (int) $this->_response->mercadoresult->productcount : 0;
	}
	
	public function num_pages() {
		
		return (isset($this->_results_per_request) && isset($this->_response->mercadoresult->productcount)) ? (int) ceil(($this->_response->mercadoresult->productcount / $this->_results_per_request)) : 1;
	}
	
	public function page() {
		
		return (isset($this->_page)) ? $this->_page : 1;
	}
	
}
