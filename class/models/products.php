<?php

class Products_Model {
	
	protected $_meta;
	
	public function __construct() {
		
	}
	
	public function meta($name = null, $value = null) {
		
		//Getter
		if(($name !== null && ! is_array($name)) && $value === null) {
			
			 return $this->_meta[$name];
		} 
		
		//Setter of single meta
		if($name !== null && $value !== null) {
			
			$this->_meta[$name] = $value;
		}
		
		//Setter for multiples
		if(($name !== null && is_array($name)) && $value === null) {
			
			foreach($name as $key=>$value) {
				
				$this->_meta[$name] = $value;
			}
		}
		
		return $this;
	}
	
	
	
	
	
}