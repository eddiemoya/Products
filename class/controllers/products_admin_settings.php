<?php

class Products_Admin_Settings {
	
	public $settings_field;
	
	public $options;
	
	public $prefix;
	
	
	public function __construct() {
		
		$this->prefix = SHC_PRODUCTS_PREFIX;
		$this->settings_field = SHC_PRODUCTS_PREFIX . "settings";
		$this->options = array('api_key' => '06749c96f1e1bfadfeca4a02b4120253',
								'store'	=> 'Sears');//Plugin_Utils::options();
		
		add_action('admin_menu', array(&$this, 'menu'));
        add_action('admin_init', array(&$this, 'register_settings'));
		
	}
	
	public function menu() {
		
		add_options_page('SK Products Settings', 'Products Settings', 'manage_options', 'skproducts-settings', array(&$this, 'settings_page'));
	}
	
	public function register_settings() {
		
		register_setting($this->settings_field, $this->settings_field);
		
		add_settings_section(SHC_PRODUCTS_PREFIX . 'api_section', __('Products API Settings'), array(&$this, 'api_section'), 'skproducts-settings');
        add_settings_field('api_key', __('Product API Key'), array(&$this, 'api_key'), 'skproducts-settings', SHC_PRODUCTS_PREFIX . 'api_section');
		add_settings_field('store', __('Store'), array(&$this, 'store'), 'skproducts-settings', SHC_PRODUCTS_PREFIX . 'api_section');
	}
	
	public function api_section() {
		
		 echo '<p>' . __('Products API parameters.') . '</p>';
	}
	
	public function api_key() {
		
		Plugin_Utils::view('form/input_text', array('name' => $this->settings_field . '[api_key]',
													'id' => SHC_PRODUCTS_PREFIX . 'api-key',
													'value' => $this->options['api_key']));
		
	}
	
	public function store() {
		
		Plugin_Utils::view('form/input_radio', array('name' => $this->settings_field . '[store]',
													'id' => SHC_PRODUCTS_PREFIX . 'store',
													'options' => array('Kmart' 		=> 'Kmart',
																		'Sears' 	=> 'Sears',
																		'MyGofer' 	=> 'MyGofer'),
													'checked' => $this->options['store']));
	}
	
	public function settings_page() {
		
		Plugin_Utils::view('admin/settings', array('settings_field' => $this->settings_field,
													'settings_section' => 'skproducts-settings'));
	}
}