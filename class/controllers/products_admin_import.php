<?php

class Products_Admin_Import {
	
	
	public function __construct() {
		
		add_action('admin_menu', array(&$this, 'submenu'));
		add_action('wp_ajax_action_list', array(&$this, 'product_list'));
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
        $method             = isset($_POST['method'])             ? $_POST['method']            : 'keyword';
        $search_terms       = isset($_POST['search_terms'])       ? $_POST['search_terms']      : '';
        $vertical_terms     = isset($_POST['vertical_terms'])     ? $_POST['vertical_terms']    : '';
        $category_terms     = isset($_POST['category_terms'])     ? $_POST['category_terms']    : '';
        $subcategory_terms  = isset($_POST['subcategory_terms'])  ? $_POST['subcategory_terms'] : '';
        $current_page       = isset($_POST['page_number'])        ? $_POST['page_number']       : 1;
        $product_count      = isset($_POST['product_count'])      ? $_POST['product_count']     : 0;
        $next_page      = $current_page + 1;
        $previous_page  = $current_page - 1;
        $start_index    = ($current_page - 1) * $num_per_page + 1;
        $end_index      = (($start_index + $num_per_page) > $product_count) && ($product_count > 0) ? $product_count : $start_index + $num_per_page - 1;
    	
    	
    	
    }
}