<?php

class Plugin_Utils {
	
	protected $_option_name;
	
	
	protected function _option_name() {
		
		$this->_option_name = SHC_PRODUCTS_PREFIX . 'settings';
	}
	
	/**
	 * autoload()
	 * 
	 * Plugins's class autoloader. Only allows for one level deep
	 * from root class directory.
	 * 
	 * @param string $class
	 */
	public static function autoload($class) {
		
		$class_dir = SHC_PRODUCTS_CLASS;
		$file = strtolower(trim($class)) . '.php';
		
		//Check class root dir first
		if(file_exists($class_dir . $file)) {
			
			require_once $class_dir . $file;
			
		} else {
			
		//Get all sub-dirs in class root dir
		$dirs = scandir($class_dir);
		
		if($dirs) {
			
			$exclude = array('...', '..', '.');
			
			foreach($dirs as $dir) {
				
				if(is_dir($class_dir . $dir) && ! in_array($dir, $exclude)) {
					
					if(is_file($class_dir . $dir . '/' . $file)) {
						
						require_once $class_dir . $dir . '/' . $file;
						return;
					}
				}
					
			}
		}
		
		}
	
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $name
	 * @param unknown_type $value
	 */
	public static function options($name = null, $value = null) {
		
		//Set option prefix property
		$this->_option_name();
		
		//Get plugin options
		$options = get_option($this->_option_name);
		
		//Return entire settings array
		if($name === null && $value === null && $options) {
			
			return $options;
		}
		
		//Get a specific element from options
		if(($name !== null && $value === null) && isset($options[$name]) ) {
			
			return $options[$name];
		}
		
		//Set plugin option
		if($name === null && is_array($value)) {
			
			return update_option($this->_option_name, $value);
		}
		
		return null;
	}
	
	public static function view($view, array $args = null) {
		
		$file = SHC_PRODUCTS_VIEWS . $view . '.php';
		
		if($args !== null)
			extract($args, EXTR_SKIP);
			
		ob_start();
		
		if(is_file($file)) {
			
			include $file;
		}
		
		echo ob_get_clean();
		
	}
	
	
	
}