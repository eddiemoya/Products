<?php



//add_action('admin_init', 'enqueue_assets');

function enqueue_assets() {
	
	$scripts_dir = SHC_PRODUCTS_PATH . 'assets/js/';
	$styles_dir = SHC_PRODUCTS_PATH . 'assets/css/';
	
	
	
	wp_register_script('jquery-tools', $scripts_dir . 'jquery.tools.min.js',array(), '1.0');
	wp_enqueue_script('jquery-tools');
	
}

