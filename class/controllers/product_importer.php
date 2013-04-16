<?php

class Product_Importer {
	
	public $partnumbers;
	
	public $errors = array();
	
	public $msg = array();
	
	public $num_products_imported = 0;
	
	public $num_products_fail_import = 0;
		
	
	public function __construct(array $partnumbers) {
		
		$this->partnumbers = $partnumbers;
	}
	
	public static function factory(array $partnumbers) {
		
		return new Product_Importer($partnumbers);
	}
	
	public function import() {
		
		if(count($this->partnumbers)) {
			
			foreach($this->partnumbers as $partnumber) {
				
				$product = Products_Api_Request::factory(array('api' => 'detail',
																'term' => $partnumber))
												->response();

				//Check that we got a valid response
				if($product->success){
					
					$products_model = new Products_Model;
					
					//Check if product already exists in WP, if not proceed
					if(! $products_model->exists('partnumber', $partnumber))
					
						//Insert product post and post meta
						$insert = Products_Model::factory()
												->post_args(array('post_status'		=> 'publish',
																'post_title'	=> $product->descriptionname,
																'post_content'	=> $product->longdescription,
																'post_excerpt'	=> $product->shortdescription
																))
												->meta(array('product_line'				=> $product->prodline,
															'product_attributes'	=> ($product->prodline == 'soft') ? $product->product_attributes : null,
															'product_attr_values'	=> ($product->prodline == 'soft') ? $product->product_attr_values : null,
															'product_variants'		=> ($product->prodline == 'soft') ? $product->variants : null,
															'colorswatch_images'	=> ($product->prodline == 'soft') ? $product->colorswatch_images : null,
															'catentryid'			=> $product->catentryid,
															'saleprice'				=> $product->saleprice,
															'regularprice'			=> $product->regularprice,
															'brandname'				=> $product->brandname,
															'partnumber'			=> $partnumber,
															'catalogid'				=> $product->catalogid,
															'rating'				=> $product->rating,
															'numreview'				=> $product->numreview,
															'imageurls'				=> $product->imageurls,
															'mainimageurl'			=> $product->mainimageurl,
															'product_uri'			=> $product->product_uri
															))
												->save();

						if($insert) {
							
							$this->num_products_imported++;
							
						} else {
							
							$this->num_products_fail_import++;
							
							$this->_set_error($partnumber . 'was NOT imported - post insert failed.');
						}
					
				} else {
					
					$this->num_products_fail_import++;
					
					$this->_set_error($partnumber . ' was NOT imported - not found in API.');
				}
				
			}
			
				if(count($this->errors)) {
					
					$this->_set_msg('There were issues importing some of the products: ');
					
				} else {
					
					$this->_set_msg('All products were imported successfully. ' . $this->num_products_imported . ' products imported');
				}
			
		} else {
			
			$this->_set_msg('Please select at least one product to import.');
		}
		
		return $this;
		
	}
	
	protected function _set_msg($text) {
		
		$this->msg[] = $text;
	}
	
	protected function _set_error($error) {
		
		$this->errors[] = $error;
	}
	
}