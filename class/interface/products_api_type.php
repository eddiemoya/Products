<?php

interface Products_Api_Type {
	
	public function load();
	public function response();
	public function num_pages();
	public function num_products();
	public function page();
	
}