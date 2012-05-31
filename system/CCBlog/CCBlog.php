<?php
/**
* A blog controller to display a blog-like list of all content labelled as "post".
* 
* @package LydiaCore
*/

class CCBlog extends CObject implements IController {

  public function __construct() {
    parent::__construct();
  }
  
  public function index() {
    $content = new CMContent();
    $this->views->setTitle('Blog')
                ->addInclude(__DIR__ . '/index.tpl.php', array(
                  'contents' => $content->listAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC')),
                ));
  }

  

}
