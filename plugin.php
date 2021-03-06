<?php
/*
Plugin Name: Sears/Kmart Products Plugin
Description: Provides functionality to import product data from Sears Product API and use products within WP.
Version: 1.0
Author: Dan Crimmins
*/

 //Define plugin root, class, and function paths
 define('SHC_PRODUCTS_PATH', WP_PLUGIN_DIR . '/products/');
 define('SHC_PRODUCTS_ASSETS_URL', plugins_url('assets/', __FILE__));
 define('SHC_PRODUCTS_CLASS', SHC_PRODUCTS_PATH . 'class/');
 define('SHC_PRODUCTS_FUNCTION', SHC_PRODUCTS_PATH . 'function/');
 define('SHC_PRODUCTS_VIEWS', SHC_PRODUCTS_PATH . 'views/');
 
 //Define any misc global data
 define('SHC_PRODUCTS_POSTTYPE', 'product');
 define('SHC_PRODUCTS_PREFIX', 'sk_products_');
 
 
 //Include Product_Utils class - contains class autoloader
 require_once(SHC_PRODUCTS_CLASS . 'product_utils.php');
 require_once(SHC_PRODUCTS_FUNCTION . 'functions.php');
 
 
 //Register autoload function
 spl_autoload_register(array('Product_Utils', 'autoload'));
 
 //Install / Uninstall
 register_activation_hook(__FILE__, array('Product_Utils', 'install'));
 register_deactivation_hook(__FILE__, array('Product_Utils', 'uninstall'));
    
 
 //Initialize classes with hooks
 Product_Utils::init();
 
