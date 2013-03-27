<?php

class Products_Admin_Import {
	
	public $num_per_page = 20;
	
	
	
	public function __construct() {
		
		add_action('admin_menu', array(&$this, 'submenu'));
		add_action('wp_ajax_product_list', array(&$this, 'product_list'));
		add_action('wp_ajax_product_import', array(&$this, 'product_import'));
	}
	
	public function submenu() {
		
        add_submenu_page( 'edit.php?post_type=' . SHC_PRODUCTS_POSTTYPE, __('Import Products'), __('Import Products'), 'edit_products', 'import', array(&$this, 'index'));
    }
    
    public function index() {
    	
    	Plugin_Utils::view('admin/import/index');
    }
    
    public function product_list() {
    	
        $search_terms       = isset($_POST['search_terms']) ? $_POST['search_terms'] : '';
        $current_page       = isset($_POST['page_number'])  ? $_POST['page_number'] : 1;
    	
        $results = Products_Api_Request::factory(array('api'  => 'search',
														'type' => 'keyword',
														'term' => $search_terms,
														'page' => $current_page,
														'per_page' => $this->num_per_page))
										->response();
		
		
        $args = array('products'			=> ($results->success) ? $results->products : null,
        				'search_term'		=> $search_terms,
        				'product_count'		=> $results->num_products,
        				'current_page'		=> $current_page,
        				'num_pages'			=> $results->num_pages,
        				'next_page'			=> (($current_page + 1) < $results->num_pages) ? $current_page + 1 : null,
        				'prev_page'			=> ($current_page != 1) ? $current_page - 1 : null,
        				'start_index'		=> ($current_page != 1 && ($current_page - 3) >= 1) ? $current_page - 3 : 1,
        				'end_index'			=> (($current_page + 3) <= $results->num_pages) ? $current_page + 3 : $results->num_pages);
        
        $view = Plugin_Utils::view('admin/import/list', $args, true);
        
        echo $view;
        exit;
    }
    
    public function product_import() {
    	
    	$partnumbers = (isset($_POST['import_single'])) ? $_POST['import_single'] : null;
    	$search_term = $_POST['search_term'];
        $current_page = isset($_POST['page_number'])  ? $_POST['page_number'] : 1;
    	
    		
    		$import = Product_Importer::factory($partnumbers)
    									->import();

    		$results = Products_Api_Request::factory(array('api'  => 'search',
														'type' => 'keyword',
														'term' => $search_term,
														'page' => $current_page,
														'per_page' => $this->num_per_page))
											->response();
										
    		 $args = array('products'			=> ($results->success) ? $results->products : null,
	        				'search_term'		=> $search_term,
	        				'product_count'		=> $results->num_products,
	        				'current_page'		=> $current_page,
	        				'num_pages'			=> $results->num_pages,
	        				'next_page'			=> (($current_page + 1) < $results->num_pages) ? $current_page + 1 : null,
	        				'prev_page'			=> ($current_page != 1) ? $current_page - 1 : null,
	        				'start_index'		=> ($current_page != 1 && ($current_page - 3) >= 1) ? $current_page - 3 : 1,
	        				'end_index'			=> (($current_page + 3) <= $results->num_pages) ? $current_page + 3 : $results->num_pages,
	    		 			'errors'			=> (count($import->errors)) ? $import->errors : null,
	    		 			'message'			=> $import->msg);
    		
    		 $view = Plugin_Utils::view('admin/import/list', $args, true);
    		 
    		 echo $view;
    		 exit;
    }
    	
}