<?php

class Product_Utils {
	
	/**
	 * $_option_name - the plugin options name
	 * @var string
	 */
	public static $_option_name;
	
	/**
	 * $_option_defaults - default option values
	 * @var array
	 */
	public static $_option_defaults = array('api_key'					=> '06749c96f1e1bfadfeca4a02b4120253',
											'store'						=> 'Sears',
											'updater_log_root'			=> '/appl/wordpress/log/',
											'updater_email_recipient'	=> 'phpteam@searshc.com');
	
	/** 
	 * $_classes - Array of classes to load on init 
	 * @var array
	 * @see init()
	 */
	public static $_classes = array('settings_admin'			=> 'Products_Admin_Settings',
									'product_register'			=> 'Product_Post_Type',
									'product_importer'			=> 'Products_Admin_Import',
									'product_enqueue_assets'	=> 'Product_Assets');
	
	/**
	 * _option_name() - Sets $_option_name
	 * 
	 * @param void
	 * @return void
	 */
	public static function _option_name() {
		
		self::$_option_name = SHC_PRODUCTS_PREFIX . 'settings';
	}
	
	/**
	 * autoload()
	 * 
	 * Plugins's class autoloader. Only allows for one level deep
	 * from root class directory.
	 * 
	 * @param string $class
	 * @return void
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
	 * options() - Sets and gets plugin options.
	 * 
	 * @param string $name
	 * @param mixed $value
	 * @return mixed - [array | string | NULL] 
	 */
	public static function options($name = null, $value = null) {
		
		//Set option prefix property
		self::_option_name();
		
		//Get plugin options
		$options = get_option(self::$_option_name);
		
		//Return entire settings array
		if($name === null && $value === null && $options) {
			
			return $options;
		}
		
		//Get a specific element from options
		if((($name !== null && ! is_array($name)) && $value === null) && isset($options[$name]) ) {
			
			return $options[$name];
		}
		
		//Set plugin options - all
		if(($name !== null && is_array($name)) && $value === null) {
			
			return update_option($this->_option_name, $value);
		}
		
		//Set, update value of one element of options array
		if($name !== null && $value !== null) {
			
			$options[$name] = $value;
			
			return update_option($this->_option_name, $options);
		}
		
		return null;
	}
	
	/**
	 * view() - Passes args, includes and echoes/returns view
	 * 
	 * @param string $view - path the view file in view dir
	 * @param array $args - assoc. array of vars that view will use.
	 * @return void
	 */
	public static function view($view, array $args = null, $return = false) {
		
		$file = SHC_PRODUCTS_VIEWS . $view . '.php';
		
		
		if($args !== null)
			extract($args, EXTR_SKIP);
			
		ob_start();
		
		if(is_file($file)) {
			
			include $file;
		}
		
		if(! $return) {
			
			echo ob_get_clean();
			
		} else {
			
			return ob_get_clean();
		}
		
	}
	
	public static function image($url, $attrs = false) {
		
		$defaults = array('width' 		=> 140,
						  	'height'	=> 140,
							'alt'		=> '');
		if(! $attrs)
			return "<img src='{$url}' height='{$defaults["height"]}' width='{$defaults["width"]}' alt='{$defaults["alt"]}' />";
			
			
		return "<img src='{$url}' height='{$attrs["height"]}' width='{$attrs["width"]}' alt='{$attrs["alt"]}' />";
		
	}
	
	/**
	 * init() - Used to instantiate objects of classes with init hooks (ie. Admin stuff)
	 * 
	 * @param void
	 * @return void
	 */
	public static function init() {
		
		foreach(self::$_classes as $var=>$class) {
			
			$$var = new $class();
		}
	}
	
	protected function _add_capabilities() {
		
		$role = get_role('administrator');
		
		$role->add_cap('delete_products');
		$role->add_cap('edit_products');
		$role->add_cap('edit_published_products');
		$role->add_cap('edit_others_products');
		$role->add_cap('publish_products');
	}	
	
	public static function install() {
			
		update_option(SHC_PRODUCTS_PREFIX . 'settings', self::$_option_defaults);
		
		self::_add_capabilities();
		
		flush_rewrite_rules();
	}
	
	public static function uninstall() {
			
		delete_option(SHC_PRODUCTS_PREFIX . 'settings');
	}
	
	
}