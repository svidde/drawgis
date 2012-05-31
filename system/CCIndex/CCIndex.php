<?php

# CCIndex
class CCIndex extends CObject implements IController {

  
  public function __construct() {
	parent::__construct();
  }
  
  
    #Implementing interface IController. All controllers must have an index action.
  public function index() {  
  	  
    $modules = new CMModules();
  	  $this->views->setTitle('Index Controller');
  	  $this->views->addInclude( __DIR__ . '/index.tpl.php', array(), 'primary');
  	  $this->views->addInclude(__DIR__. '/sidebar.tpl.php', array('menu'=>$this->menu()),'sidebar');
  }
  
  private function menu() {
  	  $items=array();
       $mtds=array();
       foreach($this->config['controllers'] as $key =>$val)
       {
         if($val['enabled'])
         {
           $rc=new ReflectionClass($val['class']);
           $items[]=$key;
           $methods= $rc->getMethods(ReflectionMethod::IS_PUBLIC);
           
           
           foreach($methods as $method)
           {
             if($method->name !="__construct" && $method->name !='__destruct' && $method->name != 'index'  && $method->name != 'handler')
             {
               $mtds[]=mb_strtolower($method->name);
             }
           }
           sort($mtds);
           $items[]=$mtds;
           $mtds=array();
         }
       }
       
       return $items;
  }
  
  
  
  
}
   
