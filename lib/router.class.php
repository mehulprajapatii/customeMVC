<?php
class Router{
	protected $uri;
	protected $controller;
	protected $action;
	
	
	protected $params;
	protected $route;
	protected $method_prefix;
	protected $language; 
	
	public function getUri() {
		return $this->uri;
	}
	
	public function getController() {
		return  $this->controller;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function getParams() {
		return $this->params;
	}
	
	public function getRoute() {
		return $this->route;
	}
	public function getMethodPrefix() {
		return $this->method_prefix;
	}
	public function getLanguage() {
		return $this->language;
	}
	
	public function __construct($uri){
		$this->uri = urldecode(trim($uri,'/'));
		
		//print_r("Ok Router was called with uri :".$this->uri);
		
		//Get Defaults
		$routes = Config::get('routes');
		$this->route = Config::get('default_route');
		$this->method_prefix = isset($route[$this->route]) ? $route[$this->route] : '';
		$this->language = Config::get('default_language');
		$this->controller = Config::get('default_controller');
		$this->action = Config::get('default_action');
		
		
		//print_r($this->uri);
		$uri_parts = explode('?', $this->uri);
		//Get the path like /lng/controller/action/param1/param2/.....
		//echo $uri_parts."------------54";
		$path= $uri_parts[0];
		
		$path_parts = explode('/', $path);
		array_shift($path_parts)."<br>";
// 		echo "<pre>".print_r($path_parts)."</pre>";
		
		if (count($path_parts)){
			
			//Get route  or language at fist element
			if (in_array(strtolower(current($path_parts)), array_keys($routes))){
				$this->route = strtolower(current($path_parts));
				$this->method_prefix = isset( $routes[$this->route] ) ?  $routes[$this->route]: '';
				array_shift($path_parts);
				
			}elseif (in_array(strtolower(current($path_parts)), Config::get('languages'))){
				$this->language = strtolower(current($path_parts));
				array_shift($path_parts);
			}
			//Get controller  next element of array 
			if(current($path_parts)){
				$this->controller= strtolower(current($path_parts));
				array_shift($path_parts);
			}
			//GEt action
			if (current($path_parts)){
				$this->action = strtolower(current($path_parts));
				array_shift($path_parts);
			}
			
			// get the param -all the rest
			$this->params = $path_parts;
		}
	}
}