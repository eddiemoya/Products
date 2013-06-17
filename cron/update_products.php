<?php
/**
 * Usage: Run from command line.
 * 
 * Eg. php /path/to/this/update_products.php "<multisite blog #1 domain>" <blog_id>
 * 
 * IMPORTANT! This plugin must be activated in the Network Admin as well as the individual blog
 */

ini_set('memory_limit', '256M');
(PHP_SAPI === 'cli') or die('This can only be executed via command line.');
/**
 * This file contains a scheduled task to update products in wordpress.
 * It first attempts to load the wordpress environment and then
 * executes the update.
 */

//Check that arg for http host is passed
if(! $argv[1]) {
	
	throw new Exception('You must provide HTTP_HOST as arg 1');
	exit;
}

if(! $argv[2] || ! is_numeric($argv[2])) {
	
	throw new Exception('You must provide the blog ID (int) as arg 2.');
	exit;
}

$_SERVER['HTTP_HOST'] = $argv[1];
$blog_id = 1;

$wp_folder_root = substr(__FILE__, 0, (stripos(__FILE__, 'wp-content/')));

if ( ! is_dir($wp_folder_root))
{
    throw new Exception('Invalid wordpress root folder set.');
    exit;
}

$wp_folder_root = realpath($wp_folder_root) . DIRECTORY_SEPARATOR;

// Load in wordpress environment.
require_once $wp_folder_root.'wp-load.php';

switch_to_blog($argv[2]);

Products_Updater::factory()->update();
				
exit;