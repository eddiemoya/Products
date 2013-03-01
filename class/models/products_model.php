<?php

class Products_Model {
	
	protected $_meta;
	
	protected $_post_args;
	
	public function __construct() {
		
		$this->post_args('post_type', SHC_PRODUCTS_POSTTYPE);
	}
	
	public static function factory() {
		
		return new Products_Model;
	}
	
	public function meta($name = null, $value = null) {
		
		return $this->_data('_meta', $name, $value);
	}
	
	public function post_args($name = null, $value = null) {
		
		return $this->_data('_post_args', $name, $value);
	}
	
	public function save() {
		
		if(! is_wp_error($id = wp_insert_post($this->_post_args, true))) {
			
			foreach($this->_meta as $key=>$value) {
				
				update_post_meta($id, $key, $value);
			}
			
			return true;
		}
			
		return false;
	}
	
	public function exists($meta_key, $value) {

		global $wpdb;
		
		$q = "SELECT meta_id FROM {$wpdb->postmeta} WHERE meta_key = '{$meta_key}' AND meta_value = '{$value}'";
		
		return ($wpdb->get_results($q)) ? true : false;
	}
	
	protected function _data($property, $name = null, $value = null) {
		
		//Getter
		if(($name !== null && ! is_array($name)) && $value === null) {
			
			 return $this->{$property}[$name];
		} 
		
		//Setter of single meta
		if($name !== null && $value !== null) {
			
			$this->{$property}[$name] = $value;
		}
		
		//Setter for multiples
		if(($name !== null && is_array($name)) && $value === null) {
			
			foreach($name as $key=>$value) {
				
				$this->{$property}[$key] = $value;
			}
		}
		
		return $this;
	}
		
}