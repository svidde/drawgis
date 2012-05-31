<?php

class CCPage extends CObject implements IController {
	
  public function __construct() {
    parent::__construct();
  }
  
  public function index() {
    $content = new CMContent();
    $this->views->setTitle('Page')
                ->addInclude(__DIR__ . '/index.tpl.php', array(
                  'content' => null,
                ));
  }
  
  public function view($id=null) {
    $content = new CMContent($id);
    $this->views->setTitle('Page: '.htmlEnt($content['title']))
                ->addInclude(__DIR__ . '/index.tpl.php', array(
                  'content' => $content,
                ));
  }


}
