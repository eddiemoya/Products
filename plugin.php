<?php
/*
Plugin Name: Sears Products Plugin
Description: Provides functionality to import product data from Sears Product API and use products within WP.
Version: 1.0
Author: Eddie "the Moyan" Moya & Dan Crimmins
*/

 //Define plugin root, class, and function paths
 define('SHC_PRODUCTS_PATH', WP_PLUGIN_DIR . '/products/');
 define('SHC_PRODUCTS_CLASS', SHC_PRODUCTS_PATH . 'class/');
 define('SHC_PRODUCTS_FUNCTION', SHC_PRODUCTS_PATH . 'function/');
 define('SHC_PRODUCTS_VIEWS', SHC_PRODUCTS_PATH . 'views/');
 
 //Options prefix
 define('SHC_PRODUCTS_PREFIX', 'sk_products_');
 
 
 //Include Product_Utils class - contains class autoloader
 require_once(SHC_PRODUCTS_CLASS . 'plugin_utils.php');
 
 
 //Declare autoload function
 spl_autoload_register(array('Plugin_Utils', 'autoload'));
 
 
 


