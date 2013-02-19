<?php

function enqueue_assets() {
	
	$scripts_dir = get_template_directory_uri(). '/assets/js/';
	$styles_dir = get_template_directory_uri() . '/assets/css/';
	
	$scripts = array('jquery-tools' => array('path'		=> $scripts_dir . 'jquery.tools.min.js',
												'dep'	=> array(),
												'ver'	=> null),
					 'cart'			=> array('path'		=> $scripts_dir . 'cart.js',
												'dep'	=> array('jquery-tools'),
												'ver'	=> '1.0'),
					'admin'			=> array('path'		=> $scripts_dir . 'admin.js',
												'dep'	=>	array('jquery-tools'),
												'ver'	=> '1.0'),
					'front'			=> array('path' 	=> $scripts_dir . 'front.js',
												'dep'	=> array('jquery-tools'),
												'ver'	=> '1.0'),
					'related'		=> array('path'		=> $scripts_dir. 'related.js',
												'dep'	=> array('jquery-tools'),
												'ver'	=> '1.0'));
	//register scripts
	foreach($scripts as $handle => $opts) {
		
		wp_register_script($handle, $opts['path'], $opts['dep']);
	}
	wp_register_script('jquery-tools', $scripts_dir . 'jquery.tools.min.js');
	
	
}

