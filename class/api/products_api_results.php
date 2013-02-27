<?php

class Products_Api_Results {
	
	/**
	 * _raw_response - contains raw api response
	 * 
	 * @var object
	 */
	protected $_raw_response;
	
	/**
	 * api_data_type - the api repsonse type
	 * 
	 * @var string [detail | search]
	 */
	public $api_data_type;
	
	/**
	 * num_pages - the number of pages of results (search only).
	 * 
	 * @var int
	 */
	public $num_pages;
	
	/**
	 * num_products - number of products from search
	 * 
	 * @var int
	 */
	public $num_products;
	
	/**
	 * page - the current page of results
	 * 
	 * @var int
	 */
	public $page;
	
	/**
	 * data - contains overload properties
	 * 
	 * @var array
	 */
	public $data = array();
	
	/**
	 * _detail_misc_attrs - array of properties to map from 
	 * _raw_response. Root point: _raw_response->productdetail->softhardproductdetails[1][0]
	 * Only used on detail api responses.
	 * 
	 * @var array
	 * @see _set_detail_misc_attrs()
	 */
	protected $_detail_misc_attrs = array('catentryid',
											'longdescription',
											'shortdescription',
											'mainimageurl',
											'saleprice',
											'brandname',
											'partnumber',
											'descriptionname',
											'catalogid',
											'productvideourl');
	
	
	/**
	 * __construct
	 * 
	 * @param object Products_Api_Type $obj - an object that implements Interface Products_Api_Type
	 * @return void
	 * 
	 * @uses _obj_type()
	 * @uses _set_properties()
	 */
	public function __construct(Products_Api_Type $obj) {
		
		$this->_obj_type($obj);
		
		$this->_raw_response = $obj->response();
		
		$this->success = $this->_is_success($obj);
		
		$this->num_pages = $obj->num_pages();
		
		$this->num_products = $obj->num_products();
		
		$this->page = $obj->page();
		
		if($this->success)
			$this->_set_properties();
		
	}
	
	protected function _is_success($obj) {
		
		if(! $obj->success)
			return false;
			
		if($this->api_data_type == 'search')
			return (isset($this->_raw_response->mercadoresult->products) || 
					isset($this->_raw_response->mercadoresult->navgroups->navgroup)) ? true : false;
		
		if($this->api_data_type == 'detail') 
			return ($this->_raw_response->productdetail->statusdata->responsecode === 0) ? true : false;
	}
	
	/**
	 * _obj_type() - sets api_data_type property
	 * 
	 * @param object $obj (from constructor)
	 * @return void
	 */
	protected function _obj_type($obj) {
		
		if(is_a($obj, 'Products_Api_Search'))
			$this->api_data_type = 'search';
			
		if(is_a($obj, 'Products_Api_Detail'))
			$this->api_data_type = 'detail';
	}
	
	/**
	 * __get() - Magic method for retrieving overloaded properties.
	 * 
	 * @param string $name
	 * @return mixed - the value of the property being accessed
	 * or null if not found.
	 */
	public function __get($name) {
		
		if(isset($this->data[$name])) {
			
			return $this->data[$name];
		} 
		
		return null;
	}
	
	/**
	 * __set() - Magic method to set overload properties.
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		
		$this->data[$name] = $value;
	}
	
	/**
	 * __isset() - Magic method to check for the existence of an
	 * overload property.
	 * 
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name) {
		
		return isset($this->data[$name]);
	}
	
	/**
	 * _products()
	 * 
	 * Sets the $products or $navigation property based in keyword or vertical|category (used for search api only)
	 * 
	 * @param void
	 * @return void
	 */
	protected function _products() {
		
		if($this->api_data_type == 'search')
		
			if(isset($this->_raw_response->mercadoresult->products->product[1]))
				$this->products = $this->_raw_response->mercadoresult->products->product[1];
				
			if(isset($this->_raw_response->mercadoresult->navgroups->navgroup))
				$this->navigation = $this->_raw_response->mercadoresult->navgroups->navgroup[1];	
	}
	
	/**
	 * _set_properties() 
	 * 
	 * Calls either _set_search_properties or _set_detail_properties. Depending
	 * on the which api the response is from.
	 * 
	 * @param void
	 * @return void
	 * @uses _set_search_properties()
	 * @uses _set_detail_properties()
	 */
	protected function _set_properties() {
		
		if($this->api_data_type == 'search')
			
			$this->_set_search_properties();
		
		if($this->api_data_type == 'detail')
			
			$this->_set_detail_properties();
		
	}
	
	/**
	 * _set_detail_properties() 
	 * 
	 * Used to set properties for detail api response.
	 * 
	 * @param void
	 * @return void
	 * 
	 * @uses _set_prodline()
	 * @uses _set_product_attributes()
	 * @uses _set_variants()
	 * @uses _set_colorswatch_images()
	 * @uses _set_detail_misc_attrs()
	 * @uses _set_imageurls()
	 */
	protected function _set_detail_properties() {
		
		$this->_set_prodline();
		
		if($this->prodline == 'soft') {
			
			$this->_set_product_attributes();
			
			$this->_set_variants();
			
			$this->_set_colorswatch_images();
		}
		
		$this->_set_detail_misc_attrs();
		
		$this->_set_imageurls();
	
	}
	
	/**
	 * _set_search_properties()
	 * 
	 * Used to set properties for a search api response.
	 * 
	 * @param void
	 * @return void
	 * 
	 * @uses _products()
	 */
	protected function _set_search_properties() {
		
		$this->_products();
	}
	
	/**
	 * _set_prodline()
	 * 
	 * Sets prodline property ('soft' | 'hard')
	 * 
	 * @param void
	 * @return void
	 */
	protected function _set_prodline() {
		
		$this->prodline = (isset($this->_raw_response
										->productdetail
										->softhardproductdetails[1][0]
										->productvariants
										->prodlist)) ? 'soft' : 'hard';
	}
	
	/**
	 * _set_product_attributes()
	 * 
	 * Sets $product_attributes property & $product_attr_values.
	 * This is used exclusively for 'softline' products in the detail api.
	 * 
	 * 	$product_attribtes - array of attributes for a given product
	 * 		e.g. - Size, Color (will always be first letter upper case).
	 * 
	 *  $product_attr_values - an assoc array. Maps attributes to array of  attribute possible values.
	 *  	e.g.- array('Color' => array('red',
	 *  								'blue',
 	 *  								'yellow'),
	 *  				'Size'	=> array('S',
	 *  								'M',
	 *  								'L',
	 *  								'XL')
	 *  				)	
	 *  
	 * 			
	 *  @param void
	 *  @return void
	 *  
	 *  @uses _set_product_attr_values()
	 */
	protected function _set_product_attributes() {
		
		$this->product_attributes = $this->_raw_response->productdetail
														->softhardproductdetails[1][0]
														->productvariants
														->prodlist[1][0]
														->product[1][0]
														->attnames[1][0]
														->attname[1];
		
		$this->_set_product_attr_values();
	}
	
	/**
	 * _set_product_attr_values()
	 * 
	 * Sets $product_attr_values property.
	 * Maps attribute values to available attributes.
	 * 
	 * @param void
	 * @return void
	 * 
	 * @see _set_product_attributes()
	 */
	protected function _set_product_attr_values() {
		
		$this->product_attr_values = array();
		
		$attrs = $this->product_attributes;
		
		foreach($attrs as $key=>$attr) {
			
			$vals = $this->_raw_response->productdetail
										->softhardproductdetails[1][0]
										->productvariants->prodlist[1][0]
										->product[1][0]
										->prodvarlist
										->prodvar[1][0]
										->attlist
										->attdata[1][$key] 
										->avals[1][0]
										->aval[1];
									
			$product_attr_values[$attr] = $vals;
		}
		
		$this->product_attr_values = $product_attr_values;
	}
	
	/**
	 * _set_variants()
	 * 
	 * Sets $variants property which is an array of objects
	 * 
	 * 				e.g. ["variants"]=>
						    array(1) {
						      [0]=>
						      object(stdClass)#22 (8) {
						        ["itempno"]=>
						        string(11) "07520717102"
						        ["stk"]=>
						        string(4) "true"
						        ["ksn"]=>
						        string(7) "4609425"
						        ["pi"]=>
						        string(1) "0"
						        ["price"]=>
						        string(4) "26.0"
						        ["pid"]=>
						        string(8) "43432027"
						        ["attrs"]=>
						        array(2) {
						          ["Color"]=>
						          string(5) "Black"
						          ["Shoe Size"]=>
						          string(6) "Medium"
						        }
						        ["catentryid"]=>
						        string(8) "43432025"
						      }
	
	 * Sets the $variants_cnt property - the number of variants for product
	 * 
	 * Iterates over all variants of a product and removes the 'avals' property and
	 * replaces it with 'attrs' property which is an array of attributes name => attribute value.
	 * 		e.g. attrs = array('Color' => 'Red',
	 * 							'Size' => 'L')
	 * 
	 * Also adds the catentryid property to each variant object.
	 * 
	 * @param void
	 * @return void
	 * 
	 * @uses _set_variant_catentryid()
	 * 
	 */
	protected function _set_variants() {
		
		$variants = $this->_raw_response->productdetail
										->softhardproductdetails[1][0]
										->productvariants
										->prodlist[1][0]
										->product[1][0]
										->prodvarlist
										->prodvar[1][0]
									    ->skulist
										->sku[1];
				
		foreach($variants as $key=>$variant) {
			
			$new = array_combine($this->product_attributes, $variants[$key]->avals->aval[1]);
			$variants[$key]->attrs = $new;
			unset($variants[$key]->avals);
			
			$variants[$key] = $this->_set_variant_catentryid($variant, $key);
		}
		
		$this->variants = $variants;
		$this->variants_cnt = count($this->variants);
	}
	
	/**
	 * _set_variant_catentryid()
	 * 
	 * @param object $obj - a variant object
	 * @param int $key - array key
	 * @return object - the variant object
	 * 
	 * @see _set_variants()
	 */
	private function _set_variant_catentryid($obj, $key) {
		
		$cat_ids = $this->_raw_response
						->productdetail
						->softhardproductdetails[1][0]
						->skulist
						->sku[1];
						
		$obj->catentryid = $cat_ids[$key]->catentryid;
		
		return $obj;
	}
	
	/**
	 * _set_imageurls()
	 * 	
	 * Sets $imageurls property.
	 * Is an array of image urls for a product.
	 * 
	 * ["imageurls"]=> array(1) {
							      [0]=>
							      string(58) "http://c.shld.net/rpx/i/s/i/spin/image/spin_prod_731204012"
							    }
					 	    
	 * @param void
	 * @return void
	 */
	protected function _set_imageurls() {
		
		$this->imageurls = $this->_raw_response->productdetail
												->softhardproductdetails[1][0]
												->imageurls
												->imageurl[1];
	}
	
	
	/**
	 * _set_detail_misc_attrs()
	 * 
	 * Sets properties from properties in _raw_response using the values
	 * in $_detail_misc_attrs property.
	 * 
	 * @param void
	 * @return void
	 */
	protected function _set_detail_misc_attrs() {
		
		foreach($this->_detail_misc_attrs as $attr) {
			
			if(isset($this->_raw_response
							->productdetail
							->softhardproductdetails[1][0]
							->{$attr}))
									
				$this->{$attr} = $this->_raw_response
										->productdetail
										->softhardproductdetails[1][0]
										->{$attr};
		}
	}
	
	/**
	 * Sets $colorswatch_images
	 * 
	 * Array of image urls that maps to a color
	 * 
	 * @param void
	 * @param return
	 */
	
	protected function _set_colorswatch_images() {
		
		$this->colorswatch_images = $this->_raw_response
										->productdetail
										->softhardproductdetails[1][0]
										->productvariants
										->prodlist[1][0]
										->product[1][0]
										->prodvarlist
										->prodvar[1][1]
										->colorswatch[1];
	}
	
}
