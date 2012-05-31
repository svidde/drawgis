<?php

class CCDeveloper extends CObject implements IController {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index()  {
		$this->menu();
	}
	
	public function displayObject() {
		$this->menu();
		
		$this->data['main'] .= <<<EOD
		<h2>Dumping content of CDevelorer</h2>
		<p>Here is the content of the controller, including properties from 
		CObject which holds access to common resources in CLydia.</p>
EOD;
		$this->data['main'] .= '<pre>' . htmlentities(print_r($this, true)) . '</pre>';
	}
	
	public function links()  {
		$this->menu();
		
		$url = 'developer/links';
		$current = $this->request->createUrl($url);
		
		$this->request->cleanUrl = false;
		$this->request->querystringUrl = false;
		$default  = $this->request->createUrl($url);
		
		$this->request->cleanUrl = true;
		$clean = $this->request->createUrl($url);
		
		$this->request->cleanUrl = false;
		$this->request->querystringUrl = true;
		$querystring = $this->request->createUrl($url);
		
		$this->data['main'] .= <<<EOD
		<h2>CRequest::createUrl()</h2>
		<p>Here is a list of urls created using above method with various settings. All links should lead to
		this same page.</p>
		<ul>
		<li><a href='$current'>This is the current setting</a>
		<li><a href='$default'>This would be the default url</a>
		<li><a href='$clean'>This should be a clean url</a>
		<li><a href='$querystring'>This should be a querystring like url</a>
		</ul>
		<p>Enables various and flexible url-strategy.</p>
EOD;
	}
	
	private function menu()  {
		$menu = array('developer', 'developer/index', 'developer/links', 'developer/display-object');
		
		$html = null;
		
		foreach( $menu as $val ) {
			$html .= "<li><a href='" . $this->request->createUrl($val) . "'>$val</a>";
		}
		
		$this->data['title'] = "The Developer Controller";
		$this->data['main'] = <<<EOD
		<h1>The Developer Controller</h1>
		<p>This is what you can do for now:</p>
		<ul>
		$html
		</ul>
EOD;
  }
	
}
