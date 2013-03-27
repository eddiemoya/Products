<?php

class Product_Post_Type {
	
	public $_args;
	public $_labels;
							
	public function __construct() {
		
		$this->_set_labels();
		$this->_set_args();
		
		add_action('init', array($this, 'register'));
	}
	
	public static function factory() {
		
		return new Product_Post_Type;
	}
	
	protected function _set_args() {
		
		$this->_args = array('labels' 				=> $this->_labels,
    						'public' 				=> true,
    						'publicly_queryable' 	=> true,
    						'show_ui' 				=> true, 
    						'show_in_menu' 			=> true, 
    						'query_var' 			=> true,
    						'rewrite' 				=> array( 'slug' => 'product' ),
							'map_meta_cap'			=> true,
    						'capability_type' 		=> array('product', 'products'),
    						'has_archive' 			=> true, 
    						'hierarchical' 			=> true,
							'exclude_from_search'	=> true,
							'taxonomies'			=> array('category', 'post_tag', 'skcategory'),
    						'menu_position' 		=> null,
							'can_export'			=> true,
    						'supports' 				=> array( 'title', 'editor', 'thumbnail', 'page-attributes'));
	}
	
	protected function _set_labels() {
		
		$this->_labels =  array('name' 					=> 'Products',
						    	'singular_name'			=> 'Product',
				 				'add_new' 				=> 'Add New',
						   	 	'add_new_item' 			=> 'Add New Product',
						    	'edit_item' 			=> 'Edit Product',
						    	'new_item' 				=> 'New Product',
						    	'all_items' 			=> 'All Products',
						    	'view_item' 			=> 'View Product',
						    	'search_items' 			=> 'Search Products',
						    	'not_found' 			=> 'No products found',
						    	'not_found_in_trash' 	=> 'No products found in Trash', 
						    	'parent_item_colon' 	=> '',
						    	'menu_name' 			=> 'Products');
	}
	
	public function register() {
		
		register_post_type(SHC_PRODUCTS_POSTTYPE, $this->_args);
	}
	
	
}