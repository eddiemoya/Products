<?php

class Products_Model {
	
	protected $_meta;
	
	protected $_post_args;
	
	public $products;
	
	public $not_found;
	
	public function __construct($partnumber = null) {
		
		$this->post_args('post_type', SHC_PRODUCTS_POSTTYPE);
		
		if($partnumber) $this->get($partnumber);
	}
	
	public static function factory($partnumber = null) {
		
		return new Products_Model($partnumber);
	}
	
	public function meta($name = null, $value = null) {
		
		return $this->_data('_meta', $name, $value);
	}
	
	public function post_args($name = null, $value = null) {
		
		return $this->_data('_post_args', $name, $value);
	}
	
	public function save() {
		
		if(! is_wp_error($id = wp_insert_post($this->_post_args, true))) {
			
			foreach((array)$this->_meta as $key=>$value) {
				
				update_post_meta($id, $key, $value);
			}
			
			return true;
		}
			
		return false;
	}
	
	public function update() {
		
		if(! is_wp_error($id = wp_update_post($this->_post_args, true))) {
			
			foreach((array)$this->_meta as $key=>$value) {
				
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
	
	public function get($partnumber) {
		
		if(is_array($partnumber)) {
			
			$this->_get_multiple($partnumber);
			
		} else {
			
			$this->post_args(array('meta_query'	=> array(array('key'	=> 'partnumber',
																'value'	=> $partnumber)),
									'post_status'	=> 'publish'));
									
			$this->products = get_posts($this->_post_args);
			$this->_add_meta();
		}
		
		return $this;
	}
	
	public function get_by_id($post_id) {
		
		if(is_array($post_id)) {
			
			$this->_get_multiple_by_id($post_id);
			
		} else {
			
			$this->products = array(get_post($post_id));
			$this->_add_meta();
		}
		
		return $this;
	}

	protected function _get_multiple($partnumber) {
		
		foreach($partnumber as $pid) {
			
			$this->post_args(array('meta_query'		=> array(array('key'	=> 'partnumber',
																	'value'	=> $pid)),
									'post_status'	=> 'publish'));
			
			$posts = get_posts($this->_post_args);
			
			if(! $posts) {
				
				//try import
				$this->_import($pid);
				
			} else {
				
				$this->products[] = $posts[0];
			}
			
		}
		
		$this->_add_meta();
	}
	
	protected function _get_multiple_by_id($post_id) {
		
		foreach($post_id as $id) {
			
			$post = get_post($id);
			
			if($post) {
				
				$this->products[] = $post;
			}
		}
		
		$this->_add_meta();
	}
	
	protected function _import($partnumber) {
		
		$importer = new Product_Importer((array)$partnumber);
		$importer->import();
		
		if($importer->num_products_fail_import) {
			
			//if import failed
			$this->not_found[] = $partnumber;
			
		} else {
			
			//import was successful
			$product = Products_Model::factory($partnumber)->products[0];
			$this->products[] = $product;
		}
	}
	
	protected function _add_meta() {
		
		if($this->products) {
			
			foreach($this->products as $key=>$product) {
				
				$meta = $this->_convert_meta(get_post_meta($product->ID, ''));
				
				$this->products[$key]->meta = $meta;
				
				unset($meta);
			}
		}
	}
	
	protected function _convert_meta($meta) {
		
		$data = new stdClass();
	
		foreach((array) $meta as $key=>$meta_value) {
			
			$value = @unserialize($meta_value[0]);
			
			if($value !== false) {
				
				$data->{$key} = $value;
				
			} else {
				
				$data->{$key} = $meta_value[0];
			}
		}
		
		return $data;
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