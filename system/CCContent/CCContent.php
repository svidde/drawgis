<?php


class CCContent extends CObject implements IController {


	
  public function __construct() {
  	  parent::__construct(); 
  }


  
  public function index() {
    $content = new CMContent();
    $this->views->setTitle('Content Controller')
                ->addInclude(__DIR__ . '/index.tpl.php', array(
                  'contents' => $content->listAll(),
                ));
  }
  

  public function edit($id=null) {
    $content = new CMContent($id);
    $form = new CFormContent($content);
    $status = $form->check();
    if($status === false) {
      $this->addMessage('notice', 'The form could not be processed.');
      $this->redirectToController('edit', $id);
    } else if($status === true) {
      $this->redirectToController('edit', $content['id']);
    }
    
    $title = isset($id) ? 'Edit' : 'Create';
    $this->views->setTitle("$title content: $id")
                ->addInclude(__DIR__ . '/edit.tpl.php', array(
                  'user'=>$this->user, 
                  'content'=>$content, 
                  'form'=>$form,
                ));
  }
  

  public function create() {
    $this->edit();
  }


  
  public function init() {
    $content = new CMContent();
    $content->init();
    $this->redirectToController();
  }
  

}
