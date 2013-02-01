<?php

class Products_Admin_Import {
	
	
	public function __construct() {
		
		add_action('admin_menu', array(&$this, 'submenu'));
		add_action('wp_ajax_product_list', array(&$this, 'product_list'));
	}
	
	public function submenu() {
		
        add_submenu_page( 'edit.php?post_type=' . SHC_PRODUCTS_POSTTYPE, __('Import Products'), __('Import Products'), 'edit_posts', 'import', array(&$this, 'index'));
    }
    
    public function index() {
    	
    	Plugin_Utils::view('admin/import/index');
    }
    
    public function product_list() {
    	
    	$num_per_page       = 20;
        $page_range         = 3;
        $search_terms       = isset($_POST['search_terms']) ? $_POST['search_terms'] : '';
        $current_page       = isset($_POST['page_number'])  ? $_POST['page_number'] : 1;
    	
        $results = Products_Api_Request::factory(array('api'  => 'search',
														'type' => 'keyword',
														'term' => $search_terms,
														'page' => $current_page,
														'per_page' => $num_per_page))
										->response();
		
        $args = array('products'			=> ($results->success) ? $results->products : null,
        				'product_count'		=> $results->num_products,
        				'current_page'		=> $current_page,
        				'num_pages'			=> $results->num_pages,
        				'next_page'			=> (($current_page + 1) < $results->num_pages) ? $current_page + 1 : null,
        				'prev_page'			=> ($current_page != 1) ? $current_page - 1 : null,
        				'use_page_range'	=> ($results->num_pages > ($page_range + 3)) ? true : false,
        				'page_range'		=> $page_range);
        
        $view = Plugin_Utils::view('admin/import/list', $args, true);
        
        echo $view;
        exit;
    }
}