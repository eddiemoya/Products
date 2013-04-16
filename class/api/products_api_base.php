<?php 

class Products_Api_Base {
	
	/**
	 * _params - stores params used in API call.
	 * 
	 * @var array
	 */
	protected $_params = array();
	
	
	/**
	 * _param_keys - maps api param items to plugin options (properties).
	 * 
	 * @var array
	 */
	protected $_param_keys = array('apiVersion'		=> 'api_version',
									'appID'			=>	'api_appid',
									'authID'		=>	'api_authid',
									'apikey'		=>	'api_key',
									'store'			=> 	'api_store',
									'contentType'	=> 	'_content_type'
									);
	
	/**
	 * _endpoint - the API endpoint URL
	 * @var string			
	 */
	protected $_endpoint = 'http://webservices.sears.com/shcapi/';
	
	/**
	 * _method - appended to end of _endpoint (search or detail)
	 * @var string
	 */
	protected $_method;
	
	/**
	 * $_url - the complete url to send request to ($_endpoint + $_params)
	 * @var string
	 */
	protected $_url;
	
	/**
	 * _response - the raw API response
	 * @var unknown_type
	 */
	protected $_response;
	
	/**
	 * _http_code - HTTP code returned from cURL request
	 * @var string
	 */
	protected $_http_code;
	
	/**
	 * _success - Indicates if a valid response was received from api.
	 * @var bool
	 */
	public $success = false;
	
	/**
	 * _content_type - specify the content type you want the API results in (xml or json)
	 * @var string
	 */
	protected $_content_type = 'json';	
	
	/**
	 * Array for holding overloaded properties.
	 * @var array
	 */
	public $_data = array();
	 
	protected $_curl_options = array(
							        CURLOPT_RETURNTRANSFER  => 1,
							        CURLOPT_CONNECTTIMEOUT => 300,          // timeout on connect 
							        CURLOPT_TIMEOUT        => 300,          // timeout on response
   									);
	
	
	public function __construct() {
		
		//get plugin options
		$options = Product_Utils::options();
		
		$this->api_version = 'v2';
		$this->api_key = (isset($options['api_key'])) ? $options['api_key'] : '06749c96f1e1bfadfeca4a02b4120253';
		$this->api_appid = 'FitStudio';
		$this->api_authid = 'nmktplc303B1614B51F9FE5340E87FD1A1CEB3C06222010';
		$this->api_store = (isset($options['store'])) ? $options['store'] : 'Sears';
		
		
		$this->_map_options();
	
	}

	public static function factory($api_name) {
		
		$class = 'Products_Api_' . ucfirst(strtolower($api_name));
		
		if(class_exists($class))
		
			return new $class;
	}
	
	
	protected function _param($name, $value = null) {
		
		if(is_array($name)) {
			
			foreach($name as $key=>$val) {
				
				$this->_params[$key] = $val;
			}
			
		} else {
			
			$this->_params[$name] = $value;
		}
		
		return $this;
	}
	
	public function __set($name, $value) {
		
		$this->_data[$name] = $value;
	}
	
	public function __get($name) {
		
	 if (array_key_exists($name, $this->_data)) {
	 	
            return $this->_data[$name];
      }
      
      return null;
	}
	
	public function __isset($name) {
		
		return isset($this->_data[$name]);
	}
	
	protected function _results_per_request($num = null) {
		
		if($num === null)
		 	return $this->_results_per_request;
			
		$this->_results_per_request = $num;
		
		return $this;
	}
	
	protected function _page($num = null) {
		
		if($num === null)
			return $this->_page;
			
		$this->_page = $num;
		
		return $this;
	}
	
	protected function _offset() {
		
		$results = $this->_results_per_request;
		$page = $this->_page;
		
		$offset = 0;
		
		for($i = 1; $i < $page; $i++) {
			
			$offset = ($offset + (int) $results);
		}
		
		$this->_offset = $offset;
				
	}
	
	public function limit($page_num, $num_results = 25) {
		
		$this->_page($page_num);
		
		$this->_results_per_request($num_results);
		
		$this->_offset();
		
		$end = $this->_offset + $this->_results_per_request;
		
		$this->_param(array('startIndex' => $this->_offset + 1,
							'endIndex'	 => $end));
	
		return $this;
		
	}
	
	
	protected function _num_pages($num = null) {
		
		if($num === null) 
			return $this->_num_pages;
		
		$this->_num_pages = $num;
		
		return $this;
	}
	
	protected function _method($method = null) {
		
		if($method === null) {
			
			return $this->_method;
		}
		
		$this->_method = $method;
		
		return $this;
	}
	
	protected function build_url() {
		
		$this->_url = $this->_endpoint . $this->_method() . '?' . http_build_query($this->_params);
	}
	
	protected function _map_options() {
		
		foreach($this->_param_keys as $name=>$value)
		
			$this->_param($name, $this->{$value});
	}
	
	protected function _load() {
		
		$this->build_url();
		
		 // Init the curl resource.
        $ch = curl_init($this->_url);

        // Set connection options
        if ( ! curl_setopt_array($ch, $this->_curl_options))
        {
            throw new Exception('Failed to set CURL options, check CURL documentation.');
        }

        // Get the response body
        $body = curl_exec($ch);
        
        // Get the response information
        $this->_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        //Check for an HTTP 200
        if($this->_http_code == '200')
        	$this->success = true;
        

        if ($body === FALSE)
        {
            $error = curl_error($ch);
        }

        // Close the connection
        curl_close($ch);
        
		if(! isset($error)) {
        	
        	$this->_response = json_decode($body);
        	
        }
		
		
	}
	
	
}