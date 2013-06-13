<?php

ini_set('memory_limit', '256M');
(PHP_SAPI === 'cli') or die('This can only be executed via command line.');
/**
 * This file contains a scheduled task to update products in wordpress.
 * It first attempts to load the wordpress environment and then
 * executes the update.
 */

$wp_folder_root = substr(__FILE__, 0, (stripos(__FILE__, 'wp-content/')));

if ( ! is_dir($wp_folder_root))
{
    throw new Exception('Invalid wordpress root folder set.');
    exit;
}

$wp_folder_root = realpath($wp_folder_root) . DIRECTORY_SEPARATOR;

// Load in wordpress environment.
require_once $wp_folder_root.'wp-load.php';


Products_Updater::factory()
				->update();
				
exit;