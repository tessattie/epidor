<?php
session_start();
class App
{
	protected $controller = 'home'; 
	protected $method = 'index';
	protected $params;

	public function __construct()
	{
		 error_reporting( E_ALL );
	    ini_set( "display_errors", 1 );

	    // for localhost
		define('DIRECTORY_NAME', "/administration");
		define("ROOT_DIRECTORY", "C:/wamp/www/administration");

		// for tests
		// define('DIRECTORY_NAME', "/~tessatti");
		// define("ROOT_DIRECTORY", "/home/tessatti/public_html");

		// for prod
		// define("ROOT_DIRECTORY", "/home/espaceep/public_html");
		// define('DIRECTORY_NAME', "");
		$controllerName = $this->controller;
		$methodName = $this->method;

		$_SESSION['lastPage'] = null;
		// returns array : see function below
		$url = $this->parseUrl();

		if( file_exists(ROOT_DIRECTORY.'/app/controllers/'.$url[0].'.php'))
		{
			$this->controller = $url[0];
			$controllerName = $url[0];
			unset($url[0]);
		}

		if(empty($_SESSION['id']) && $this->controller != "login")
		{
			header("Location:".DIRECTORY_NAME."/login");
		}
		require_once ROOT_DIRECTORY.'/app/controllers/'.$this->controller.'.php';
		
		$this->controller = new $this->controller;

		if(isset($url[1]))
		{
			if(method_exists($this->controller, $url[1]))
			{
				$this->method = $url[1];
				$methodName = $url[1];
				unset($url[1]);
			}
		}
		
		// verify if url has parameters, if not, pass an empty array 
		$this->params = $url ? array_values($url) : array();
		call_user_func_array(array($this->controller, $this->method), $this->params);

	}

	public function parseUrl()
	{
		if(isset($_GET['url']))
		{
			// rtrim : deletes extra slash at the end of the URL
			// filter_var : verifies that its a URL and delete all illegal caracters from $_GET['url']
			// explode : returns an array
			return $url = explode('/',filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}           
}