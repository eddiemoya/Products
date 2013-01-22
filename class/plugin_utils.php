<?php

class Plugin_Utils {
	
	public static function autoload($class) {
		
		$class_dir = SHC_PRODUCTS_CLASS;
		$file = strtolower(trim($class)) . '.php';
		
		//Check class root dir first
		if(file_exists($class_dir . $file)) {
			
			require $class_dir . $file;
			
		} else {
			
			//Get all sub-dirs in class root dir
			$dirs = scandir(SHC_PRODUCTS_CLASS);
			
				if($dirs) {
					
					$exclude = array('...', '..', '.');
					
					foreach($dirs as $dir) {
						
						if(is_dir($class_dir . $dir) && ! in_array($dir, $exclude)) {
							
							if(is_file($class_dir . $dir . '/' . $file)) {
								
								require $class_dir . $dir . '/' . $file;
								return;
							}
						}
						
						if(is_file())
					}
				}
			
		}
	}
	
	
}