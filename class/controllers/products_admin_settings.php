<?php

class Products_Admin_Settings {
	
	public $settings_field;
	
	public $options;
	
	public $prefix;
	
	
	public function __construct() {
		
		$this->prefix = SHC_PRODUCTS_PREFIX;
		$this->settings_field = SHC_PRODUCTS_PREFIX . "settings";
		$this->options = Product_Utils::options();
		
		add_action('admin_menu', array(&$this, 'menu'));
        add_action('admin_init', array(&$this, 'register_settings'));
		
	}
	
	public function menu() {
		
		add_options_page('SK Products Settings', 'Products Settings', 'manage_options', 'skproducts-settings', array(&$this, 'settings_page'));
	}
	
	public function register_settings() {
		
		register_setting($this->settings_field, $this->settings_field, array($this, 'settings_save'));
		
		//API settings
		add_settings_section(SHC_PRODUCTS_PREFIX . 'api_section', __('Products API Settings'), array(&$this, 'api_section'), 'skproducts-settings');
        add_settings_field('api_key', __('Product API Key'), array(&$this, 'api_key'), 'skproducts-settings', SHC_PRODUCTS_PREFIX . 'api_section');
		add_settings_field('store', __('Store'), array(&$this, 'store'), 'skproducts-settings', SHC_PRODUCTS_PREFIX . 'api_section');
		
		//Product updater settings
		add_settings_section(SHC_PRODUCTS_PREFIX . 'updater_section', __('Products Updater Settings'), array(&$this, 'updater_section'), 'skproducts-settings');
		add_settings_field('updater_log_root', __('Log directory root path'), array(&$this, 'updater_log_root'), 'skproducts-settings', SHC_PRODUCTS_PREFIX . 'updater_section');
		add_settings_field('updater_email_recipient', __('E-mail recipient'), array(&$this, 'updater_email_recipient'), 'skproducts-settings', SHC_PRODUCTS_PREFIX . 'updater_section');
		add_settings_field('force_update', __('Force Product Update'), array(&$this, 'force_update'), 'skproducts-settings', SHC_PRODUCTS_PREFIX . 'updater_section');
	}
	
	public function api_section() {
		
		 echo '<p>' . __('Products API parameters.') . '</p>';
	}
	
	public function updater_section() {
		
		echo '<p>' . __('Products Updater parameters.') . '</p>';
	}
	
	public function api_key() {
		
		Product_Utils::view('form/input_text', array('name' => $this->settings_field . '[api_key]',
													'id' => SHC_PRODUCTS_PREFIX . 'api-key',
													'value' => $this->options['api_key']));
		
	}
	
	public function store() {
		
		Product_Utils::view('form/input_radio', array('name' => $this->settings_field . '[store]',
													'id' => SHC_PRODUCTS_PREFIX . 'store',
													'options' => array('Kmart' 		=> 'Kmart',
																		'Sears' 	=> 'Sears',
																		'MyGofer' 	=> 'MyGofer'),
													'checked' => $this->options['store']));
	}
	
	public function updater_log_root() {
		
		Product_Utils::view('form/input_text', array('name' => $this->settings_field . '[updater_log_root]',
													'id' => SHC_PRODUCTS_PREFIX . 'updater-log-root',
													'value' => $this->options['updater_log_root']));
	}
	
	public function updater_email_recipient() {
		
		Product_Utils::view('form/input_text', array('name' => $this->settings_field . '[updater_email_recipient]',
													'id' => SHC_PRODUCTS_PREFIX . 'updater-email-recipient',
													'value' => $this->options['updater_email_recipient']));
	}
	
	public function force_update() {
		
		Product_Utils::view('form/input_radio', array('name' => $this->settings_field . '[force_update]',
														'id' => SHC_PRODUCTS_PREFIX . 'force_update',
														'options' => array('Yes' => 'yes',
																			'No' => 'no'),
														'checked' => $this->options['force_update']));
	}
	
	public function settings_save($settings) {
		
		if($settings['force_update'] == 'yes') {
			
			Products_Updater::factory(true)->update();
		}
		
		return $settings;
	}
	
	public function settings_page() {
		
		Product_Utils::view('admin/settings', array('settings_field' => $this->settings_field,
													'settings_section' => 'skproducts-settings'));
	}
}