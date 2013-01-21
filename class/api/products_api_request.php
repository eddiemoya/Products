<?php

class Products_Api_Request {

	protected $_allowed_args = array('type' => array('keyword',
													'vertical',
													'category'),
	
										'api'	=> array('search',
														 'detail'));
	
	protected $_args;
	
	protected $_obj;

	public function __construct($args) {
		
		$this->_validate_args($args);
		
		$this->_args = $args;
		
		try {
			 
			$this->$args['api']();
			
		} catch (Exception $e) {
			
			die($e->getMessage());
		}
		
	}
	
	public static function factory($args) {
		
		return new Products_Api_Request($args);
	}
	
	public function search() {
		
		$request = Products_Api_Base::factory('search')
									->{$this->_args['type']}($this->_args['term']);
									
		if(isset($this->_args['page']) && (is_numeric($this->_args['page']) && is_numeric($this->_args['per_page']))) {
		
			$request->limit($this->_args['page'], $this->_args['per_page']);		
		}
		
		$request->load();
		
		
		$this->_obj = new Products_Api_Results($request);
			
	}
	
	public function detail() {
		
	}
	
	public function response() {
		
		return $this->_obj;
	}
	
	protected function _validate_args(array $args) {
		
		if(! isset($args['api']) || ! in_array($args['api'], $this->_allowed_args['api'])) 
			throw new Exception('You must include an api attribute in args [search | detail]');
		
		if(! isset($args['type']) || ! in_array($args['type'], $this->_allowed_args['type'])) 
			throw new Exception('You must include a type attribute (category | keyword | vertical) in the args you pass.');
		
		if(! isset($args['term']))
			throw new Exception('You must include a term attribute in the args array.');
			
		if(isset($args['page']) && ! isset($args['per_page'])) 
			throw new Exception('If you include the page argument you must also include the per_page argument');
			
		if($args['type'] == 'category' && (! is_array($args['term']) || ! array_key_exists(array('vertical', 'category'), $args['term'])))
			throw new Exception('Incorrect term value for category request. Must be an array containing a category and vertical element.');
	}
}