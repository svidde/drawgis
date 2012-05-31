<?php

class CCTheme extends CObject implements IController {
	
	public function __construct() {
		parent::__construct();  
		$this->views->addStyle('body:hover{background:#fff url('.$this->request->base_url.'themes/grid/grid_12_60_20.png) repeat-y center top;}');
  
	}
	
	public function index() {
		
		$rc = new ReflectionClass(__CLASS__);
		$methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
		$items = array();
		foreach($methods as $method) {
			if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'index') {
				$items[] = $this->request->controller . '/' . mb_strtolower($method->name);
			}
		}
   
		$this->views->setTitle('Theme')
			    ->addInclude( __DIR__ . '/index.tpl.php', array(
			    	    'theme_name' => $this->config['theme']['name'],
			    	    'methods' => $items,
			    	    ));
	}
	
	public function someRegions() {
			 $this->views->setTitle('Theme display content for some regions')
			 	->addString('This is the primary region', array(), 'primary');
                
			 	if(func_num_args()) {
			 		foreach(func_get_args() as $val) {
			 			$this->views->addString("This is region: $val", array(), $val)
			 				    ->addStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
                    			}
                    		}
        }
  
  
        public function allRegions() {
        	$this->views->setTitle('Theme display content for all regions');
        	foreach($this->config['theme']['regions'] as $val) {
        		$this->views->addString("This is region: $val", array(), $val)
        			    ->addStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
        	}
        }
        
        
        public function h1h6(){
        	 $this->views->setTitle('Theme testing headers and paragraphs')
                ->addInclude(__DIR__ . '/h1h6.tpl.php', array(), 'primary');
  }
        	
	
}
