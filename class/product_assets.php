<?php
class Product_Assets {
	
	protected $_scripts_dir;
	
	protected $_styles_dir;
	
	protected $_scripts;
	
	protected $_styles;
	
	 
	
	protected $_admin_scripts = array(/*'jquery-tools' 	=> array('file'		=> 'jquery.tools.min.js',
																'dep'		=> array(),			
																'ver'		=> '1.0',
																'page'		=> null),*/
										'products_admin' => array('file'	=> 'admin.js',
																'dep'		=>	array('jquery-tools'),
																'ver'		=> '1.0',
																'page'		=> 'edit.php'));
	
	
	protected $_admin_styles = array('product-admin'	=> array('file'	=> 'admin_style.css',
																'dep'	=> array(),
																'ver'	=> '1.0',
																'page'	=> 'edit.php'));
	
	public function __construct() {
		
		$this->_set_paths();
		
		add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue'));
	}
	
	protected function _set_paths() {
		
		$this->_scripts_dir = plugins_url('assets/js/', dirname(__FILE__));
		$this->_styles_dir = plugins_url('assets/css/', dirname(__FILE__));	
	}
	
	
	protected function _load($group, $type, $hook=null) {
		
		foreach($this->{$group} as $handle=>$opts) {
			
			if($type == 'script') {
				
				//if($hook && $hook == $opts['page'])
					wp_register_script($handle, $this->_scripts_dir . $opts['file'], $opts['dep'], $opts['ver']);
					wp_enqueue_script($handle);
			}
			
			if($type == 'style') {
				
				//if($hook && $hook == $opts['page'])
					wp_register_style($handle, $this->_styles_dir . $opts['file'], $opts['dep'], $opts['ver']);
					wp_enqueue_style($handle);
			}
		}
	}
	
	public function admin_enqueue($hook) {
		
		//Admin scripts
		$this->_load('_admin_scripts', 'script');
		
		wp_localize_script('products_admin', 'shcp_ajax', array('ajaxurl' => admin_url('admin-ajax.php'), 'imageurl' => SHC_PRODUCTS_ASSETS_URL . 'images/'));
		
		//Admin styles
		$this->_load('_admin_styles', 'style');
	}
	

	public function enqueue() {
		
	}
	
	
	
}