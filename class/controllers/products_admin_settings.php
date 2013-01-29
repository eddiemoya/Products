<?php

class Products_Admin_Settings {
	
	public $settings_field;
	
	public $options;
	
	public $prefix;
	
	
	public function __construct() {
		
		$this->prefix = SHC_PRODUCTS_PREFIX;
		$this->settings_field = SHC_PRODUCTS_PREFIX . "settings";
		$this->options = Plugin_Utils::options();
	}
	
	public static function init() {
		
		$this->__construct();
		
		add_action('admin_menu', array(__CLASS__, 'menu'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
	}
	
	public function menu() {
		
		add_options_page('SK Products Settings', 'Products Settings', 'manage_options', 'skproducts-settings', array(__CLASS__, 'settings_page'));
	}
	
	public function register_settings() {
		
		register_setting($this->settings_field, $this->settings_field);
		
		add_settings_section(SHC_PRODUCTS_PREFIX . 'api_section', __('Products API Settings'), array(__CLASS__, 'api_section'), 'skproducts-settings');
        add_settings_field('product_api_key', __('Product API Key'), array(__CLASS__, 'api_key'), 'skproducts-settings', SHCSSO_OPTION_PREFIX . 'api_section');
		add_settings_field('product_store', __('Store'), array(__CLASS__, 'store'), 'skproducts-settings', SHCSSO_OPTION_PREFIX . 'store');
	}
	
	public function api_section() {
		
		 echo '<p>' . __('Product API Settings.') . '</p>';
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