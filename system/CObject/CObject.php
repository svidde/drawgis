<?php

class CObject {
	
	public $config;
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;
	public $user;
	
	protected function __construct($dr=null)  {
		if ( !$dr )
		{
			$dr = CDrawgis::getInstance();
		}
		$this->config   = &$dr->config;
		$this->request  = &$dr->request;
		$this->data     = &$dr->data;
		$this->db	= &$dr->db;
		$this->views	= &$dr->views;
		$this->session  = &$dr->session;
		$this->user  	= &$dr->user;
	}
	
	
	protected function redirectTo($urlOrController=null, $method=null) {
    $dr = CDrawgis::getInstance();
    if(isset($this->config['debug']['db-num-queries']) && $this->config['debug']['db-num-queries'] && isset($this->db)) {
      $this->session->setFlash('database_numQueries', $this->db->getNumQueries());
    }    
    if(isset($this->config['debug']['db-queries']) && $this->config['debug']['db-queries'] && isset($this->db)) {
      $this->session->setFlash('database_queries', $this->db->getQueries());
    }    
    if(isset($this->config['debug']['timer']) && $this->config['debug']['timer']) {
	    $this->session->setFlash('timer', $dr->timer);
    }    
    $this->session->storeInSession();
    header('Location: ' . $this->request->createUrl($urlOrController, $method));
  }
  
  
  protected function redirectToController( $method=null ) {
  	  $this->redirectTo( $this->request->controller, $method);
  }
  
  protected function redirectToControllerMethod( $controller=null, $method=null ) {
  	  $controller = is_null($controller) ? $this->request->controller : null;
  	  $method = is_null($method) ? $this->request->method : null;
  	  $this->redirectTo( $this->recuest->createUrl( $controller, $method ) );
  }
	
  
  
  protected function addMessage($type, $message) {
    $this->session->addMessage($type, $message);
  }
  
  
  protected function createUrl($urlOrController=null, $method=null, $arguments=null) {
    return $this->request->createUrl($urlOrController, $method, $arguments);
  }
}
