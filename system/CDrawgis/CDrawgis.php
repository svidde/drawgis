<?php

# CDrawgis
class CDrawgis implements ISingelton {
	
	
	private static $instance = null;
	public $config = array();
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;
	public $timer = array();
	
	
	#construct
	protected function __construct() 
	{
		$this->timer['first'] = microtime(true); 
		
		$dr = &$this;
		require(DRAWGIS_SITE_PATH.'/config.php');
		
		// Start a named session
		session_name($this->config['session_name']);
		session_start();
		$this->session = new CSession($this->config['session_key']);
		$this->session->populateFromSession();

		// Set default date/time-zone
		date_default_timezone_set($this->config['timezone']);
		
		if(isset($this->config['database'][0]['dsn'])) {
			$this->db = new CDatabase($this->config['database'][0]['dsn']);
  		}
  	
		$this->views = new CViewContainer();
		
		$this->user = new CMUser($this);
	}
		
	
	#GetInstance ->  ISingelton
	public static function getInstance()
	{
		if ( self::$instance == null )  {
			self::$instance = new CDrawgis();
		}
		return self::$instance;
	}
	
	
	#frontControllerRoute, check url and route ro controllers
	public function frontControllerRoute()
	{
		#step 1
		#take current url and separate to controller, method and parameters
		$this->request = new CRequest($this->config['url_type']);
		$this->request->init($this->config['base_url'], $this->config['routing']);
		
		$controller 	= $this->request->controller;
		$method 	= $this->request->method;
		$args 		= $this->request->args;
	
		
		#step 2
		#Check if there is a method in the controller classs
		$controllerExists	= isset($this->config['controllers'][$controller]);
		$controllerEnabled 	= false;
		$className         	= false;
		$classExists         	= false;
		
		if($controllerExists) {
			$controllerEnabled   = ($this->config['controllers'][$controller]['enabled'] == true);
			$className           = $this->config['controllers'][$controller]['class'];
			$classExists         = class_exists($className);
		}
		
		if($controllerExists && $controllerEnabled && $classExists)
		{
			$rc = new ReflectionClass($className);
			if($rc->implementsInterface('IController')) 
			{
				$formattedMethod = str_replace(array('_', '-'), '', $method);
				if($rc->hasMethod($formattedMethod)) 
				{
					$controllerObj = $rc->newInstance();
					$methodObj = $rc->getMethod($formattedMethod);
					if($methodObj->isPublic()) 
					{
						$methodObj->invokeArgs($controllerObj, $args);
					} 
					else 
					{
						die("404. " . get_class() . ' error: Controller method not public.');          
					}
				} 
				else 
				{
					die("404. " . get_class() . ' error: Controller does not contain method.');
				}
			} 
			else 
			{
				die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
			}
		} 
		else 
		{ 
			die('404. Page is not found.');
		}
			
	}
	
	public function themeEngineRender()
	{
		$this->session->storeInSession();
		 
		if(!isset($this->config['theme'])) {
			return;
		}
		
		$themePath    = DRAWGIS_INSTALL_PATH . '/' . $this->config['theme']['path'];
		$themeUrl     = $this->request->base_url . $this->config['theme']['path'];
		
		$parentPath = null;
		$parentUrl = null;
		if(isset($this->config['theme']['parent'])) {
			 $parentPath = DRAWGIS_INSTALL_PATH . '/' . $this->config['theme']['parent'];
			 $parentUrl   = $this->request->base_url . $this->config['theme']['parent'];
		}
		
		# add stylesheet to the $dr->data array
		$this->data['stylesheet'] = $this->config['theme']['stylesheet'];
		
		
		// Make the theme urls available as part of $ly
		$this->themeUrl = $themeUrl;
   		$this->themeParentUrl = $parentUrl;
   		
   		// Map menu to region if defined
   		if(is_array($this->config['theme']['menu_to_region'])) {
   			foreach($this->config['theme']['menu_to_region'] as $key => $val) {
   				$this->views->addString($this->drawMenu($key), null, $val);
   			}
   		}
		
		
		// Include the global functions.php and the functions.php that are part of the theme
		$dr = &$this;
		// First the default Lydia themes/functions.php
		include( DRAWGIS_INSTALL_PATH . '/themes/functions.php');
		// Then the functions.php from the parent theme
		if($parentPath) {
			if(is_file("{$parentPath}/functions.php")) {
				include "{$parentPath}/functions.php";
			}
		}
		// And last the current theme functions.php
		if(is_file("{$themePath}/including.php")) {
			include "{$themePath}/including.php";
		}

		
		
		// Extract $ly->data to own variables and handover to the template file
		extract($this->data);  // OBSOLETE, use $this->views->GetData() to set variables
		extract($this->views->getData());
		if(isset($this->config['theme']['data'])) {
			extract($this->config['theme']['data']);
		}

		// Execute the template file
		$templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
		if(is_file("{$themePath}/{$templateFile}")) 
		{
			include("{$themePath}/{$templateFile}");
    		} 
    		else if(is_file("{$parentPath}/{$templateFile}")) 
    		{
    			include("{$parentPath}/{$templateFile}");
    		}
    		else 
    		{
    			throw new Exception('No such template file.');
    		}
    	}	
    	


	
	
	
	
	
	public function redirectTo($urlOrController=null, $method=null, $arguments=null) 
	{
		if(isset($this->config['debug']['db-num-queries']) && $this->config['debug']['db-num-queries'] && isset($this->db)) {
			$this->session->setFlash('database_numQueries', $this->db->getNumQueries());
		}    
		if(isset($this->config['debug']['db-queries']) && $this->config['debug']['db-queries'] && isset($this->db)) {
			$this->session->setFlash('database_queries', $this->db->getQueries());
		}    
		if(isset($this->config['debug']['timer']) && $this->config['debug']['timer']) {
			$this->session->setFlash('timer', $this->timer);
		}    
		$this->session->storeInSession();
		header('Location: ' . $this->request->createUrl($urlOrController, $method, $arguments));
		exit;
	}


	public function redirectToController($method=null, $arguments=null) 
	{
		$this->redirectTo($this->request->controller, $method, $arguments);
	}


	public function redirectToControllerMethod($controller=null, $method=null, $arguments=null) 
	{
	  $controller = is_null($controller) ? $this->request->controller : null;
	  $method = is_null($method) ? $this->request->method : null;	  
	  $this->redirectTo($this->request->createUrl($controller, $method, $arguments));
	}


	
	public function addMessage($type, $message, $alternative=null) 
	{
		if($type === false) 
		{
			$type = 'error';
			$message = $alternative;
		} 
		else if($type === true) 
		{
			$type = 'success';
		}
		$this->session->addMessage($type, $message);
	}


	public function createUrl($urlOrController=null, $method=null, $arguments=null) 
	{
		return $this->request->createUrl($urlOrController, $method, $arguments);
	}



	public function drawMenu($menu) {
		$items = null;
		if(isset($this->config['menus'][$menu])) 
		{
			foreach($this->config['menus'][$menu] as $val) 
			{
				$selected = null;
				if($val['url'] == $this->request->request || $val['url'] == $this->request->routed_from) 
				{
					$selected = " class='selected'";
				}
				$items .= "<li><a {$selected} href='" . $this->createUrl($val['url']) . "'>{$val['label']}</a></li>\n";
			}
		} 
		else 
		{
			throw new Exception('No such menu.');
		}     
		return "<ul class='menu {$menu}'>\n{$items}</ul>\n";
	}


}
	
